<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeRouteFile extends Command
{
    protected $signature = 'make:route-file {name}';
    protected $description = 'Create a new API route file';

    public function handle()
    {
        $name = $this->argument('name');
        $path = base_path("routes/{$name}.php");
        
        if (File::exists($path)) {
            $this->error("Route file already exists!");
            return;
        }
        
        $stub = <<<'EOD'
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::get('/test', function () {
    return response()->json(['message' => 'API is working!']);
});
EOD;

        File::put($path, $stub);
        $this->info("Route file created successfully: routes/{$name}.php");
    }
}