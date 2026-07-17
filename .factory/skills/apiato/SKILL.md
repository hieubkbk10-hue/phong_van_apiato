---
name: apiato
description: Build and review Laravel Apiato 11.x Porto APIs. Use when creating Containers, Actions, Tasks, Requests, Repositories, Transformers, CRUD endpoints, Hash ID handling, RequestCriteria, fieldSearchable search/filter APIs, or debugging Apiato/Laravel backend architecture.
version: 1.0.0
---

# Apiato 11.x Porto API Skill

Use this skill for Laravel / Apiato 11.x backend work in Porto architecture.

## Quick mental model

Apiato is not classic MVC. Think in **business domains** and **single-responsibility layers**.

```txt
Route -> Request -> Controller -> Action -> Task -> Repository/Model -> Transformer
```

- **Container** = business domain / bounded context, not necessarily one model.
- **Model** = database table representation.
- **Action** = one complete use case.
- **Task** = one reusable small job.
- **Repository** = data access adapter and query criteria surface.
- **Transformer** = public JSON response shape.
- **Event/Listener** = decouple side effects from core use case.

## When starting a feature

1. Sketch a tiny UI/wireframe first if requirements are unclear.
2. Identify resources/models, REST endpoints, payloads, response shape, permissions.
3. Decide Container by business domain:
   - Same lifecycle/context, keep together: `Order`, `OrderItem`, `OrderHistory`.
   - Independent capability, separate Container: `Payment`, `Notification`, `Chat`.
4. Prefer existing Container/Task/Action if the feature belongs there.
5. Treat production requirements as design inputs from the start, not later optimizations: indexes, pagination, authorization, validation caps, rollback, rate limits, and query shape.

## Production-grade rule priority

Use these as hard rules when generating or reviewing Apiato code:

- **Index first**: every foreign key, frequent filter, sort, and compound filter+sort query needs a matching DB index in the migration.
- **Pagination first**: list APIs must paginate or enforce a safe limit. No unbounded `all()`/`get()` for user-facing lists.
- **Transaction in Action**: when a use case writes multiple tables or calls multiple write Tasks, wrap the orchestration in the Action using `DB::transaction()` or Apiato `transactionalRun()`. Do not hide multi-step workflow transactions inside a low-level Task.
- **Task stays atomic**: Task performs one job and should be reusable outside the original use case.
- **Request is the gate**: validate, authorize, decode hash IDs, cap strings, cap list query params before Action.
- **Repository is the query contract**: expose only safe searchable fields through `$fieldSearchable`.
- **Transformer is public contract**: hide DB internals, return hashed IDs, avoid secret/internal fields.
- **Events are side-effect boundaries**: use Events/Listeners for notifications, audit logs, integrations, cache invalidation, broadcasts, and async work. Do not put core required state changes only in a Listener.
- **Frontend convenience must remain bounded**: RequestCriteria is useful, but only with allowlisted fields, indexes, max limits, and include definitions.

## Container guidance

- Default section for this repo/company style: `AppSection`.
- Container name: `PascalCase`, domain-focused: `Book`, `Order`, `Payment`.
- API-only projects usually choose `API` UI.
- Prefer **SAC** (single action controller): one controller method/file per endpoint.
- Do not create a new Container just because there is a new table.

## Layer rules

### Route

- Location: `UI/API/Routes`.
- File pattern: `{ActionName}.v1.private.php` or `{ActionName}.v1.public.php`.
- Private routes use auth middleware/guard.
- **Quy tắc viết DocBlock cho tài liệu API (`apidoc`)**:
  - Bắt buộc viết DocBlock comment `@api` đầy đủ ở đầu mỗi file Route mới. Không được bỏ sót.
  - Phân biệt rõ các thẻ để tránh lỗi cảnh báo (warnings):
    - **`@apiParam`**: Chỉ dùng cho tham số nằm trong URL (ví dụ: `/orders/:id` thì dùng `@apiParam {String} id`).
    - **`@apiBody`**: Dùng cho tham số truyền trong Request Body (ví dụ: JSON payload của POST/PUT/PATCH).
    - **`@apiQuery`**: Dùng cho tham số truyền qua URL Query (ví dụ: `?limit=15`).
  - Chạy lệnh `php artisan apiato:apidoc` đầu ra phải sạch sẽ, không có bất kỳ warning nào.
  - **Ví dụ cấu trúc DocBlock chuẩn**:
    ```php
    /**
     * @apiGroup           Order
     * @apiName            UpdateOrder
     *
     * @api                {PATCH} /v1/orders/:id Update Order
     * @apiDescription     Cập nhật thông tin đơn hàng
     *
     * @apiHeader          {String} accept=application/json
     * @apiHeader          {String} authorization=Bearer
     *
     * @apiParam           {String} id ID của đơn hàng nằm trên URL (bắt buộc)
     *
     * @apiBody            {String} [shipping_carrier] Đơn vị vận chuyển (truyền trong JSON body)
     * @apiBody            {Number} [shipping_fee] Phí giao hàng (truyền trong JSON body)
     *
     * @apiQuery           {String} [include] Load các quan hệ (truyền qua URL query, ví dụ: ?include=items)
     *
     * @apiSuccessExample  {json} Success-Response:
     * HTTP/1.1 200 OK
     * {
     *     "data": {
     *         "object": "Order",
     *         "id": "XyZ123"
     *     }
     * }
     */
    ```
- If route has `@api` docs, update it when endpoint behavior changes.



### Request

- Location: `UI/API/Requests`.
- One Request per endpoint.
- Must define `rules(): array` and `authorize(): bool`.
- Use:
  - `$access` for roles/permissions.
  - `$decode` for hashed IDs from body/query.
  - `$urlParameters` for route params like `{id}`.
- Put route ID in both `$urlParameters` and `$decode` when validating hashed URL IDs.
- Use `sanitizeInput([...])` in Actions for create/update payload allowlisting.
- Always cap strings with `max`, especially create/update fields.
- Use `sometimes` validation rule for fields in Update Requests to allow partial resource updates.
- Validate list query params: `limit`, `page`, `search`, `searchFields`, `orderBy`, `sortedBy`, `filter`, `include`.
- For create/update, align validation max lengths with database column lengths.
- For private endpoints, never leave `authorize()` as `true` unless intentionally public-to-authenticated and documented.


### Controller

- Keep thin.
- Accept Request, call Action, return response/Transformer.
- No DB query, no business orchestration.

### Action

- Orchestrates use case.
- Bắt buộc lập kế hoạch và phân rã đầy đủ các Task cần thiết trước khi viết Action.
- Calls one or more Tasks. Điều phối 100% qua các Task, tuyệt đối không viết logic truy vấn DB hay xử lý Eloquent trực tiếp trong Action.
- Owns workflow-level transactions when multiple write Tasks must succeed/fail together.
- Throw meaningful Apiato/Ship exceptions.

### Task

- One small job (Single Responsibility Principle - SRP).
- Phân rã triệt để: Khi thực hiện một luồng nghiệp vụ phức tạp, phải tạo đầy đủ từng Task nhỏ độc lập (ví dụ: tạo riêng Task lấy chi tiết, Task tạo mới, Task trừ kho...) thay vì gom nhiều logic khác nhau vào một Task duy nhất làm mất đi tính tái sử dụng.
- Do not accept Request object.
- Do not call Action.
- Avoid Task calling Task unless the existing codebase has an explicit pattern.
- Use Repository for data access.
- Catch DB/library failures and throw standard exceptions.
- Do not start broad workflow transactions in Task. Only use local transaction in a Task for a truly atomic low-level data operation that cannot be split.


### Repository

- Location: `Data/Repositories`.
- Extends `App\Ship\Parents\Repositories\Repository`.
- Use `model(): string` when model discovery is not obvious or model/container names differ.
- Repository is the frontend-friendly query surface via RequestCriteria and `$fieldSearchable`.

### Transformer

- Return public API shape only.
- Use `$model->getHashedKey()` for IDs.
- Do not leak password, token, secret, internal IDs, system-only flags.
- Define `$availableIncludes` / `$defaultIncludes`.
- Relationship include method: `include{RelationName}()`.
- Do not run heavy DB queries inside Transformer.

### Event and Listener

- Events are Laravel events with Apiato placement/rules.
- Event location: `{Container}/Events` or `Ship/Events` for truly global events.
- Listener location: `{Container}/Listeners`.
- Event class extends `App\Ship\Parents\Events\Event`.
- Listener class extends `App\Ship\Parents\Listeners\Listener`.
- Register event/listener mappings in a container EventServiceProvider, then register that provider in the container `MainServiceProvider`.
- Apiato docs allow firing events from Actions or Tasks and recommend choosing one place. For production code:
  - Prefer firing domain events **after the state change succeeds**.
  - If the Action owns a DB transaction, dispatch after the transaction commits or use queued listeners with `$afterCommit = true`.
  - Do not dispatch side effects before a transaction can still roll back.
- Use Events/Listeners for side effects:
  - notification/email/SMS
  - audit/activity log
  - cache invalidation
  - search indexing
  - webhook/integration
  - broadcast/realtime
- Do not use Listeners for required core writes that must be immediately consistent with the main use case.
- Slow or external listeners should implement `ShouldQueue`, set queue/retry/failure policy, and be idempotent.

## Repository search/filter API

Use this aggressively for list APIs so frontend can search/sort/filter without backend writing many custom endpoints.

```php
protected $fieldSearchable = [
    'name' => 'like',
    'id' => '=',
    'email' => '=',
    'email_verified_at' => '=',
    'created_at' => 'like',
];
```

Task pattern:

```php
public function run(): mixed
{
    return $this->addRequestCriteria()->repository->paginate();
}
```

Common frontend query params:

```txt
?search=John
?search=name:John
?search=name:John;email:john@example.com
?searchFields=name:like;email:=
?searchJoin=and
?orderBy=created_at&sortedBy=desc
?filter=id;name;email
?include=roles,permissions
?limit=20&page=2
```

Important:

- `search` works only when RequestCriteria is applied.
- Only allow safe fields in `$fieldSearchable`.
- Add DB indexes for searchable/sortable fields used often.
- Validate/cap `limit`, never allow unbounded list responses.
- For hashed search fields, pass decode fields to `addRequestCriteria(null, ['field_id'])`; `id` is decoded by default in Apiato docs.
- Includes require Transformer include definitions and real model relationships.

## Hash ID

- Return IDs with `getHashedKey()`.
- Decode incoming hashed IDs through Request `$decode`.
- Route params need `$urlParameters`.
- Tests should send hashed IDs, e.g. model `getHashedKey()` or test helper `injectId()`.
- Never change hash salt/key in production.

## Event/Listener best practices

Use Event/Listener when adding the side effect directly to Action/Task would create coupling.

Good event names:

```txt
UserRegisteredEvent
OrderPaidEvent
BookCreatedEvent
PasswordUpdatedEvent
```

Good listener names:

```txt
SendWelcomeEmailListener
WriteOrderAuditLogListener
ClearProductCacheListener
SyncOrderToCrmListener
BroadcastOrderPaidListener
```

Registration shape:

```php
protected $listen = [
    OrderPaidEvent::class => [
        SendOrderPaidNotificationListener::class,
        WriteOrderAuditLogListener::class,
    ],
];
```

Queued listener shape:

```php
class SendOrderPaidNotificationListener extends ParentListener implements ShouldQueue
{
    public bool $afterCommit = true;
    public int $tries = 3;
    public string $queue = 'listeners';

    public function handle(OrderPaidEvent $event): void
    {
        // side effect only
    }
}
```

Rules:

- Event should be a small data carrier, no business logic.
- Prefer passing model ID or minimal immutable payload for queued/external side effects.
- Listener `handle()` should call Action/Task/service if it needs business/data access logic.
- Listener should be idempotent because queued listeners can retry.
- Add tests with `Event::fake()` for dispatch and listener tests for side effects.
- In production deploys using event discovery/cache, remember `php artisan event:cache` / `event:clear` as appropriate.

## CRUD checklist

- Migration: table/column naming, constraints, indexes, FK delete behavior, soft delete if needed.
- Model: `$fillable`, `$casts`, `$hidden`, relationships.
- Request: validation, authorization, `$decode`, `$urlParameters`, query param caps.
- Action: `sanitizeInput`, orchestration, transaction if multi-write.
- Task: repository calls, exceptions, no Request.
- Repository: model binding, `$fieldSearchable`, pagination.
- Transformer: hashed ID, safe fields, includes.
- Events/Listeners: side effects, queue, after-commit, idempotency.
- Tests: success, validation fail, unauthorized, not found, edge case.

## Migration and model checklist

- Table name plural `snake_case`; columns `snake_case`.
- Define proper column type, nullable, default, unique, precision.
- Add indexes at migration time for foreign keys, `where`, `orderBy`, and common compound filters.
- Define FK delete behavior deliberately: cascade, set null, or restrict.
- Use `softDeletes()` for important business records when deletion history matters.
- Model must define `$fillable`; avoid `$guarded = []`.
- Model should define `$casts`, `$hidden`, and relationships.

## Performance and safety

- No `all()` for list APIs.
- Use pagination/limit.
- Filter in DB, not PHP after fetch.
- Watch N+1 and eager load where needed.
- Index filters/sorts.
- Do not call DB in loops if batch is possible.
- Do not log secrets.
- Do not expose raw SQL errors.
- Use Telescope/Debugbar/query logs for query count and N+1 checks before production.
- Add rate limits for login, forgot password, search-heavy, import/export, and abuse-prone endpoints.
- Cache only when cache key, permissions, invalidation, and data freshness are clear.

## Frontend integration contract

- API response shape must be stable enough for TypeScript types.
- Support UI states: loading, error, empty, success, unauthorized.
- Return validation errors in a form-friendly shape.
- Do not make frontend depend on raw database IDs or internal DB columns.
- RequestCriteria endpoints should document allowed search/filter/order/include fields for frontend.

## Definition of done

- Wireframe/API contract understood.
- Migration includes constraints and indexes, not just columns.
- Request validates, authorizes, decodes, and caps inputs.
- Action/Task/Repository responsibilities are clean.
- Multi-write workflow transaction lives at Action level.
- List APIs paginate, avoid N+1, and use indexed filters.
- Transformer returns safe hashed response.
- Side effects are decoupled with Event/Listener when appropriate, queued after commit if slow/external.
- Tests/validators run or explicit blocker documented.
- No secrets, logs, dumps, cache, or unrelated files included.

## Generator reminders

Apiato 11.x docs list generators such as:

```txt
php artisan apiato:generate:container
php artisan apiato:generate:action
php artisan apiato:generate:task
php artisan apiato:generate:request
php artisan apiato:generate:controller
php artisan apiato:generate:route
php artisan apiato:generate:model
php artisan apiato:generate:repository
php artisan apiato:generate:transformer
php artisan apiato:generate:event
php artisan apiato:generate:listener
php artisan apiato:generate:test:functional
php artisan apiato:generate:test:unit
```

Use `--help` before relying on exact flags.

## Validation

Run suitable checks before handoff:

```txt
composer validate --strict
vendor/bin/pint --test
vendor/bin/psalm --config=psalm.dist.xml
vendor/bin/phpunit
```

For legacy repos, scoped checks on changed/staged files are acceptable during iteration, but full checks are preferred before release.

## References

- Official Apiato 11.x query parameters: https://apiato.io/docs/11.x/core-features/query-parameters/
- Official Apiato 11.x requests: https://apiato.io/docs/11.x/main-components/requests/
- Official Apiato 11.x repositories: https://apiato.io/docs/11.x/optional-components/repositories/
- Official Apiato 11.x code generator: https://apiato.io/docs/11.x/core-features/code-generator/
- Hash ID docs: https://apiato.io/docs/11.x/core-features/hash-id/
- Detailed local notes: [references/apiato-11-notes.md](references/apiato-11-notes.md)
