<?php

namespace Marifhasan\PrintPDF\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;

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

		$this->makeTempFolder();
		$this->makeCssFile();
		$this->makeClassFile($name);
		$this->makeBladeFile($name);
    }

	private function makeTempFolder(): void
	{
		$temp_folder = storage_path("printpdf/.gitignore");
		if(! file_exists($temp_folder)) {
			$content = file_get_contents(__DIR__ . '/../../../stubs/.gitignore.stub');
			$this->make($temp_folder, $content);
		}
    }

	private function makeCssFile(): void
	{
		$css_file_name = base_path("resources/css/printpdf.css");
		if(! file_exists($css_file_name)) {
			$content = file_get_contents(__DIR__ . '/../../../stubs/Style.stub');
			$this->make($css_file_name, $content);
		}
    }

	private function makeClassFile($name): void
	{
		$blade_file_name = $this->nameFormatConverter($name, "-");
		$variable_name = $this->nameFormatConverter($name, "_");
		$title_name = Str::upper($this->nameFormatConverter($name, " "));

        $content = file_get_contents(__DIR__ . '/../../../stubs/Print.stub');
        $content = str_replace('___NAME___', $name, $content);
        $content = str_replace('___VARIABLE_NAME___', $variable_name, $content);
        $content = str_replace('___TITLE___', $title_name, $content);
        $content = str_replace('___BLADE___', $blade_file_name, $content);

		$this->make(base_path("app/Prints/{$name}.php"), $content);
    }

	private function makeBladeFile($name): void
	{
		$layout_file_name = base_path("resources/views/prints/layout.blade.php");
		if(! file_exists($layout_file_name)) {
			$content = file_get_contents(__DIR__ . '/../../../stubs/Layout.stub');
			$this->make($layout_file_name, $content);
		}

		$header_file_name = base_path("resources/views/prints/partials/header.blade.php");
		if(! file_exists($header_file_name)) {
			$content = file_get_contents(__DIR__ . '/../../../stubs/Header.stub');
			$this->make($header_file_name, $content);
		}

		$blade_file_name = $this->nameFormatConverter($name, "-");
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

	private function nameFormatConverter($name, $variable = "-"): string
	{
		return strtolower(preg_replace('/([a-zA-Z])(?=[A-Z])/', '$1' . $variable, $name));
	}
}
