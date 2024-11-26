<?php

namespace Tests\Feature\Assets\Api;

use App\Models\Asset;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AssetFilesTest extends TestCase
{
    public function testAssetApiAcceptsFileUpload()
    {
        // Upload a file to an asset

        // Create an asset to work with
        $asset = Asset::factory()->count(1)->create();

	// Create a superuser to run this as
	$user = User::factory()->superuser()->create();

	//Upload a file
	$this->actingAsForApi($user)
            ->post(
<<<<<<< HEAD
               route('api.assets.files', ['asset_id' => $asset[0]["id"]]), [
=======
               route('api.assets.files.store', ['asset_id' => $asset[0]["id"]]), [
>>>>>>> origin/upstream
		       'file' => [UploadedFile::fake()->create("test.jpg", 100)]
	       ])
	       ->assertOk();
    }

    public function testAssetApiListsFiles()
    {
        // List all files on an asset
        
        // Create an asset to work with
        $asset = Asset::factory()->count(1)->create();

	// Create a superuser to run this as
	$user = User::factory()->superuser()->create();

	// List the files
	$this->actingAsForApi($user)
            ->getJson(
<<<<<<< HEAD
		    route('api.assets.files', ['asset_id' => $asset[0]["id"]]))
=======
		    route('api.assets.files.index', ['asset_id' => $asset[0]["id"]]))
>>>>>>> origin/upstream
                ->assertOk()
		->assertJsonStructure([
                    'status',
		    'messages',
		    'payload',
		]);
    }

    public function testAssetApiDownloadsFile()
    {
        // Download a file from an asset

        // Create an asset to work with
        $asset = Asset::factory()->count(1)->create();

	// Create a superuser to run this as
	$user = User::factory()->superuser()->create();

	//Upload a file
	$this->actingAsForApi($user)
            ->post(
<<<<<<< HEAD
               route('api.assets.files', ['asset_id' => $asset[0]["id"]]), [
=======
               route('api.assets.files.store', ['asset_id' => $asset[0]["id"]]), [
>>>>>>> origin/upstream
		       'file' => [UploadedFile::fake()->create("test.jpg", 100)]
	       ])
	       ->assertOk();

	// List the files to get the file ID
	$result = $this->actingAsForApi($user)
            ->getJson(
<<<<<<< HEAD
		    route('api.assets.files', ['asset_id' => $asset[0]["id"]]))
=======
		    route('api.assets.files.index', ['asset_id' => $asset[0]["id"]]))
>>>>>>> origin/upstream
                ->assertOk();

	// Get the file
	$this->actingAsForApi($user)
            ->get(
<<<<<<< HEAD
               route('api.assets.file', [
=======
               route('api.assets.files.show', [
>>>>>>> origin/upstream
                   'asset_id' => $asset[0]["id"],
                   'file_id' => $result->decodeResponseJson()->json()["payload"][0]["id"],
	       ]))
	       ->assertOk();
    }

    public function testAssetApiDeletesFile()
    {
        // Delete a file from an asset

        // Create an asset to work with
        $asset = Asset::factory()->count(1)->create();

	// Create a superuser to run this as
	$user = User::factory()->superuser()->create();

	//Upload a file
	$this->actingAsForApi($user)
            ->post(
<<<<<<< HEAD
               route('api.assets.files', ['asset_id' => $asset[0]["id"]]), [
=======
               route('api.assets.files.store', ['asset_id' => $asset[0]["id"]]), [
>>>>>>> origin/upstream
		       'file' => [UploadedFile::fake()->create("test.jpg", 100)]
	       ])
	       ->assertOk();

	// List the files to get the file ID
	$result = $this->actingAsForApi($user)
            ->getJson(
<<<<<<< HEAD
		    route('api.assets.files', ['asset_id' => $asset[0]["id"]]))
=======
		    route('api.assets.files.index', ['asset_id' => $asset[0]["id"]]))
>>>>>>> origin/upstream
                ->assertOk();

	// Delete the file
	$this->actingAsForApi($user)
            ->delete(
<<<<<<< HEAD
               route('api.assets.file', [
=======
               route('api.assets.files.destroy', [
>>>>>>> origin/upstream
                   'asset_id' => $asset[0]["id"],
                   'file_id' => $result->decodeResponseJson()->json()["payload"][0]["id"],
	       ]))
	       ->assertOk();
    }
}
