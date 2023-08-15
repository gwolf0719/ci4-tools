<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class GenerateController extends BaseCommand
{
    protected $group       = 'Generators';
    protected $name        = 'generate:controller';
    protected $description = 'Generates a controller file based on the provided template.';

    public function run(array $params)
    {
        $tableName = array_shift($params);
        if (empty($tableName)) {
            CLI::error('You must provide a table name.');
            return;
        }

        $controllerName = ucfirst($tableName);
        $modelName = 'Mod' . $controllerName;

        // Read the template content from the specified location
        $templatePath = APPPATH . 'Commands/Templates/TableName.php';
        $templateContent = file_get_contents($templatePath);
        // Replace placeholders with actual table name
        $controllerContent = str_replace('TableName', $controllerName, $templateContent);
        $controllerContent = str_replace('tablename', strtolower($tableName), $controllerContent);
        $controllerContent = str_replace('ModTableName', $modelName, $controllerContent);

        // Write the controller file
        $path = APPPATH . 'Controllers/' . $controllerName . '.php';
        file_put_contents($path, $controllerContent);

        CLI::write("Controller generated successfully at $path!", 'green');
    }
}
