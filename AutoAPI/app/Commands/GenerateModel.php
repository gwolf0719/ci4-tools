<?php namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class GenerateModel extends BaseCommand
{
    protected $group       = 'Generators';
    protected $name        = 'generate:model';
    protected $description = 'Generates a model file based on the table structure.';

    public function run(array $params)
    {
        $tableName = array_shift($params);
        if (empty($tableName)) {
            CLI::error('You must provide a table name.');
            return;
        }

        $database = \Config\Database::connect();

        // Get the table structure
        $fields = $database->getFieldData($tableName);

        // Detect primary key
        $primaryKey = '';
        foreach ($fields as $field) {
            if ($field->primary_key) {
                $primaryKey = $field->name;
                break;
            }
        }

        // Start building the model file content
        $modelName = 'Mod' . ucfirst($tableName);
        $modelContent = "<?php namespace App\Models;\n\nuse CodeIgniter\Model;\n\nclass $modelName extends Model\n{\n";
        $modelContent .= "    protected \$table      = '$tableName';\n";
        $modelContent .= "    protected \$primaryKey = '$primaryKey';\n\n"; // Use detected primary key
        $modelContent .= "    protected \$returnType     = 'array';\n";
        $modelContent .= "    protected \$useSoftDeletes = true;\n\n";

        // Fill allowed insert and update fields
        $allowedFields = array_map(function ($field) {
            return "'{$field->name}'";
        }, $fields);
        $modelContent .= "    protected \$allowedFields = [" . implode(', ', $allowedFields) . "];\n\n";

        $modelContent .= "    protected \$useTimestamps = true;\n";
        $modelContent .= "    protected \$createdField  = 'created_at';\n";
        $modelContent .= "    protected \$updatedField  = 'updated_at';\n";
        $modelContent .= "    protected \$deletedField  = 'deleted_at';\n\n";

        $modelContent .= "    // Add other properties and methods as needed\n";
        $modelContent .= "}\n";

        // Write the model file
        $path = APPPATH . 'Models/' . $modelName . '.php';
        file_put_contents($path, $modelContent);

        CLI::write("Model generated successfully at $path!", 'green');
    }
}
