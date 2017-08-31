<?php

namespace Code16\Sharp\Tests\Unit\Form\Eloquent\Utils;

use Code16\Sharp\Form\Eloquent\Uploads\SharpUploadModel;
use Code16\Sharp\Tests\Fixtures\Person;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Testing\FileFactory;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

trait TestWithSharpUploadModel
{

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        Schema::create('sharp_upload_models', function (Blueprint $table) {
            $table->increments('id');
            $table->morphs('model');
            $table->string('model_key')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('disk')->default('local');
            $table->unsignedInteger('size')->nullable();
            $table->text('custom_properties')->nullable();
            $table->unsignedInteger('order')->nullable();
            $table->timestamps();
        });

        config(['filesystems.disks.local' => [
            'driver' => 'local',
            'root' => storage_path('app'),
        ]]);

        config(['sharp.uploads.thumbnails_dir' => 'thumbnails']);

        File::deleteDirectory(storage_path("app/data"));
        File::deleteDirectory(public_path("thumbnails"));
    }

    protected function createImage()
    {
        $file = (new FileFactory)->image("test.png", 600, 600);

        return $file->store("data");
    }

    /**
     * @param $file
     * @param null $model
     * @param string $modelKey
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    protected function createSharpUploadModel($file, $model = null, $modelKey = "test")
    {
        return SharpUploadModel::create([
            "file_name" => $file,
            "size" => 120,
            "mime_type" => "image/png",
            "disk" => "local",
            "model_type" => $model ? get_class($model) : Person::class,
            "model_id" => $model ? $model->id : Person::create(["name" => "A"])->id,
            "model_key" => $modelKey
        ]);
    }
}