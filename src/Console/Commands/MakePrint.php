<?php

namespace Marifhasan\PrintPDF\Console\Commands;

use Illuminate\Console\Command;

class MakePrint extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:print {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new pdf print class';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $name = $this->argument('name');

		$this->makeCssFile();
		$this->makeClassFile($name);
		$this->makeBladeFile($name);
    }

	private function makeCssFile(): void
	{
		$css_file = base_path("resources/css/print.css");
		if(! file_exists($css_file)) {
			$content = file_get_contents(__DIR__ . '/../../../stubs/Style.stub');
			$this->make($css_file, $content);
		}
    }

	private function makeClassFile($name): void
	{
		$blade_file_name = $this->camel2dashed($name);

        $content = file_get_contents(__DIR__ . '/../../../stubs/Print.stub');
        $content = str_replace('___NAME___', $name, $content);
        $content = str_replace('___BLADE___', $blade_file_name, $content);

		$this->make(base_path("app/Prints/{$name}.php"), $content);
    }

	private function makeBladeFile($name): void
	{
		$layout_file = base_path("resources/views/prints/layout.blade.php");
		if(! file_exists($layout_file)) {
			$content = file_get_contents(__DIR__ . '/../../../stubs/Layout.stub');
			$this->make($layout_file, $content);
		}

		$header_file = base_path("resources/views/prints/partials/header.blade.php");
		if(! file_exists($header_file)) {
			$content = file_get_contents(__DIR__ . '/../../../stubs/Header.stub');
			$this->make($header_file, $content);
		}

		$blade_file_name = $this->camel2dashed($name);
        $content = file_get_contents(__DIR__ . '/../../../stubs/Blade.stub');
		$this->make(base_path("resources/views/prints/{$blade_file_name}.blade.php"), $content);
    }

	private function make($file_path, $content): void
	{
		$file_directory = dirname($file_path);
        if (!file_exists($file_directory)) {
            mkdir($file_directory, 0777, true);
        }
        $fp = fopen($file_path, 'wb');
        fwrite($fp, $content);
        fclose($fp);
	}

	private function camel2dashed($name): string
	{
		return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1-', $name));
	}
}
