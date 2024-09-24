# Nginx-Laravel11 - filament

## Tạo trang Product
Tạo migration products
```cmd
php artisan make:migration create_products_table
```
```php
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('price');
            $table->timestamps();
        });
    }
```
Tạo Model Product
```cmd
php artisan make:model Product
```
```php
class Product extends Model
{
 
    protected $fillable = [
        'name', 
        'price'
    ];
```
Triễn khai DB
```cmd
php artisan migrate
```
Tạo reource cho Product
```cmd
php artisan make:filament-resource Product
```
Thành công:
![Product option](./img/product-option.png)

Resource của Product chứa trong: 
```cmd
app/Filament/Resources/ProductResource
```
![Product option](./img/product-resource.png)

#### Chức năng
1.  CreateProduct
2.  EditProduct
3.  ListProduct

#### Resource
-   ProductResource

### Tạo Form để nhập Product và xuất columns product ra table
```php
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name'),
                Forms\Components\TextInput::make('price'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'), 
                Tables\Columns\TextColumn::make('price'),
            ]);
    }
```
Product form create/update
![Product option](./img/product-create.png)
![Product option](./img/product-update.png)
Product column on list
![Product option](./img/product-list.png)
Product column on list bulk action
![Product option](./img/product-bulkactions.png)

##### Đổi chuyển hướng sau khi create
```cmd
app/Filament/Resources/ProductResource/Pages/CreateProduct.php
```
```php
class CreateProduct extends CreateRecord
{
    // ...
    protected function getRedirectUrl(): string 
    { 
        return $this->getResource()::getUrl('index'); 
    } 
}
```
##### hoặc update
```cmd
app/Filament/Resources/ProductResource/Pages/EditProduct.php
```
```php
class EditProduct extends EditRecord
{
    // ...
    protected function getRedirectUrl(): string 
    { 
        return $this->getResource()::getUrl('index'); 
    } 
}
```
##### Thêm chức năng delete trên list
```php
 public static function table(Table $table): Table
    {
        return $table
            ...
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
    }
```

```php
<div class="fi-input-wrp flex rounded-lg shadow-sm ring-1 transition duration-75 bg-white dark:bg-white/5 [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-2 ring-gray-950/10 dark:ring-white/20 [&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-600 dark:[&:not(:has(.fi-ac-action:focus))]:focus-within:ring-primary-500 fi-fo-select">
    <div class="min-w-0 flex-1">
        <div class="choices" data-type="select-one" tabindex="0" role="combobox" aria-autocomplete="list" aria-haspopup="true" aria-expanded="false">
            <div class="choices__inner">
                <select x-ref="input" class="h-9 w-full rounded-lg border-none bg-transparent !bg-none choices__input" id="data.category_id" hidden="" tabindex="-1" data-choice="active"></select>
                <div class="choices__list choices__list--single">
                    <div class="choices__placeholder choices__item">Chọn một tùy chọn</div>
                </div>
            </div>
            <div class="choices__list choices__list--dropdown" aria-expanded="false">
                <input type="search" name="search_terms" class="choices__input choices__input--cloned" autocomplete="off" autocapitalize="off" spellcheck="false" role="textbox" aria-autocomplete="list" aria-label="Select an option" placeholder="Bắt đầu gõ để tìm kiếm..." oninput="filterOptions()">
                <div class="choices__list" role="listbox" id="options-list">
                    @foreach($getRecord()->{$getViewData()->get('relationshipName')}->all()->pluck($getViewData()->get('relationshipTitleName'), 'id') as $id => $name)
                        <div id="choices--datacategory_id-item-choice-{{ $id }}" class="choices__item choices__item--choice choices__item--selectable" role="option" data-choice="" data-id="{{ $id }}" data-value="{{ $id }}" data-choice-selectable="" aria-selected="false">
                            {{ $name }}
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function filterOptions() {
        const input = document.querySelector('input[name="search_terms"]');
        const filter = input.value.toLowerCase();
        const options = document.querySelectorAll('#options-list .choices__item');

        options.forEach(option => {
            const text = option.innerText.toLowerCase();
            option.style.display = text.includes(filter) ? '' : 'none';
        });
    }

    document.addEventListener('click', function(event) {
        const dropdownBtn = document.querySelector('.choices');
        const dropdown = document.querySelector('.choices__list--dropdown');
        const button = document.querySelector('.fi-input-wrp');

        if (!dropdown.contains(event.target) && !button.contains(event.target)) {
            dropdown.classList.add('is-active');
            dropdownBtn.classList.add('is-open is-focus');
        } else {
            dropdown.classList.remove('is-active');
            dropdownBtn.classList.remove('is-open is-focus');
        }
    });
</script>
```
