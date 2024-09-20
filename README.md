# Nginx-Laravel11 - filament

## Thêm chức năng yêu cầu cho name và price
Thêm rule là unique cho name (phát sinh lỗi nếu có soft_delete)
Thêm rule số cho price
```php
{
return $form
    ->schema([
        Forms\Components\TextInput::make('name')
            ->required()
            ->unique(),
        Forms\Components\TextInput::make('price')
            ->required()
            ->rule('numeric'),
    ])
```
1 vài rule trên laravel
https://laravel.com/docs/10.x/validation#available-validation-rules

## Thêm Sorting cho column trên table
Thêm mặc định sort cho table theo price - desc
Mở rộng chức năng search text cho columns name

```php
return $table
    ->columns([
        Tables\Columns\TextColumn::make('name')
            ->sortable() // sorting cho name
            ->searchable(isIndividual: false, isGlobal: true), // search cho name
        Tables\Columns\TextColumn::make('price')
            ->sortable() // sorting cho price
            ->label('Price - $') // đổi label cho column
            ->money('usd') // định dạng tiền
            ->getStateUsing(function (Product $record): float { //State giá trị hiện tại của row trả về
                return $record->price / 100; //giá trị price xuất ra bằng price hiện tại chia /100
            }),
    ])
    ->defaultSort('price', 'desc') // mặc định sorting ủa table
```

## Chỉnh sửa dữ liệu trước khi lưu DB
Create product
```php
class CreateProduct extends CreateRecord
{
    ....
    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['price'] = $data['price'] * 100;

        return $data;
    }
}
```
Update product
```php
class EditProduct extends EditRecord
{
    ...
    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['price'] = $data['price'] / 100;
 
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['price'] = $data['price'] * 100;
 
        return $data;
    }
```