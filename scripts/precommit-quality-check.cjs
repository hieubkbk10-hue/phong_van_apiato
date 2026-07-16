const { spawnSync } = require('node:child_process');
const fs = require('node:fs');
const path = require('node:path');

const root = path.resolve(__dirname, '..');
const logDir = path.join(root, 'storage', 'logs');
const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
const logFile = path.join(logDir, `pre-commit-${timestamp}.log`);
const latestLogFile = path.join(logDir, 'pre-commit-latest.log');

fs.mkdirSync(logDir, { recursive: true });

function appendLog(content) {
    fs.appendFileSync(logFile, content);
    fs.appendFileSync(latestLogFile, content);
}

function resetLatestLog() {
    fs.writeFileSync(latestLogFile, '');
}

function vendorBin(name) {
    return process.platform === 'win32'
        ? path.join('vendor', 'bin', `${name}.bat`)
        : path.join('vendor', 'bin', name);
}

function quote(value) {
    return `"${String(value).replace(/"/g, '\\"')}"`;
}

function run(label, command) {
    const header = `\n\n=== ${label} ===\n$ ${command}\n`;
    process.stdout.write(header);
    appendLog(header);

    const result = spawnSync(command, {
        cwd: root,
        shell: true,
        encoding: 'utf8',
        env: {
            ...process.env,
            FORCE_COLOR: '1',
        },
    });

    const output = `${result.stdout || ''}${result.stderr || ''}`;
    process.stdout.write(output);
    appendLog(output);

    if (result.status !== 0) {
        throw new Error(`${label} failed with exit code ${result.status}`);
    }

    return output;
}

function output(command) {
    const result = spawnSync(command, {
        cwd: root,
        shell: true,
        encoding: 'utf8',
    });

    if (result.status !== 0) {
        throw new Error((result.stderr || result.stdout || '').trim());
    }

    return (result.stdout || '').trim();
}

function getStagedPhpFiles() {
    return output('git diff --cached --name-only --diff-filter=ACMR')
        .split(/\r?\n/)
        .filter(Boolean)
        .filter((file) => file.endsWith('.php'))
        .filter((file) => fs.existsSync(path.join(root, file)));
}

function lintStagedPhpFiles(files) {
    if (files.length === 0) {
        const message = '\nNo staged PHP files to syntax-check.\n';
        process.stdout.write(message);
        appendLog(message);
        return;
    }

    files.forEach((file) => {
        run(`PHP syntax: ${file}`, `php -l ${quote(file)}`);
    });
}

function blockIfPintChangedStagedPhp(files) {
    if (files.length === 0) {
        return;
    }

    const changedFiles = output(`git diff --name-only -- ${files.map(quote).join(' ')}`)
        .split(/\r?\n/)
        .filter(Boolean);

    if (changedFiles.length === 0) {
        return;
    }

    const message = [
        '',
        'Pint formatted staged PHP files. Commit is blocked so you can review and re-stage them:',
        ...changedFiles.map((file) => `- ${file}`),
        '',
        'Run: git add <formatted-files>',
        '',
    ].join('\n');

    process.stderr.write(message);
    appendLog(message);
    throw new Error('Pint changed staged PHP files');
}

function runPsalmForStagedPhp(files) {
    if (files.length === 0) {
        const message = '\nNo staged PHP files to analyze with Psalm.\n';
        process.stdout.write(message);
        appendLog(message);
        return;
    }

    const psalmOutput = run(
        'Psalm static analysis for staged PHP files',
        `${quote(vendorBin('psalm'))} --config=psalm.dist.xml --show-info=true --no-progress ${files.map(quote).join(' ')}`,
    );

    if (/^INFO:/m.test(psalmOutput)) {
        throw new Error('Psalm reported INFO-level issues in staged PHP files');
    }
}

resetLatestLog();
appendLog(`Pre-commit quality check started at ${new Date().toISOString()}\n`);

try {
    const stagedPhpFiles = getStagedPhpFiles();

    run('Composer validation', 'composer validate --strict');
    run('Git staged whitespace check', 'git diff --cached --check');
    lintStagedPhpFiles(stagedPhpFiles);
    run('Laravel Pint auto-format dirty PHP files', `${quote(vendorBin('pint'))} --dirty`);
    blockIfPintChangedStagedPhp(stagedPhpFiles);
    runPsalmForStagedPhp(stagedPhpFiles);

    const message = `\nPre-commit quality check passed.\nLog: ${logFile}\n`;
    process.stdout.write(message);
    appendLog(message);
} catch (error) {
    const message = [
        '',
        'Pre-commit quality check failed.',
        `Reason: ${error.message}`,
        `Copy this log for AI Agent: ${logFile}`,
        `Latest log shortcut: ${latestLogFile}`,
        '',
    ].join('\n');

    process.stderr.write(message);
    appendLog(message);
    process.exit(1);
}
