

Детальная sql схема
```php
$this->model->where('(? OR @curseId IS NULL)', curseId: $courseId);
```