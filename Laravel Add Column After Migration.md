To add new columns to a table after a migration in Laravel, follow these steps:

### Step 1: Create a New Migration
You need to generate a new migration file to modify the table.

Run the following command in your terminal:

```bash
php artisan make:migration add_columns_to_table_name --table=table_name
```

Replace `table_name` with the name of your table.

### Step 2: Define the New Columns in the Migration
Open the newly created migration file in the `database/migrations` directory. Inside the `up` method, use the `table` method to add the new columns. Here's an example:

```php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToTableName extends Migration
{
    public function up()
    {
        Schema::table('table_name', function (Blueprint $table) {
            $table->string('new_column_1');
            $table->integer('new_column_2')->nullable();
        });
    }

    public function down()
    {
        Schema::table('table_name', function (Blueprint $table) {
            $table->dropColumn(['new_column_1', 'new_column_2']);
        });
    }
}
```

### Step 3: Run the Migration
Apply the migration using the following command:

```bash
php artisan migrate
```
or

```bash
php artisan migrate --path=database/migrations/table_name.php
```

Make sure to replace `table_name.php` with the actual filename of your migration.

### Why Use `--path`?
Using the `--path` option ensures that only the specified migration is executed without affecting other pending migrations.

### Notes
- The `up` method adds the new columns to the table.
- The `down` method removes those columns in case you roll back the migration (`php artisan migrate:rollback`).
- You can use various column types like `string`, `integer`, `boolean`, `timestamp`, etc. Refer to the [Laravel documentation on schema builder](https://laravel.com/docs/master/migrations#available-column-types) for more details.

Let me know if you need help with any specific part!