<?php

namespace Database\Seeders;

use App\Helpers\Helpers;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RemoveDefaultImagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Identifiez les chemins des images par défaut
        $defaultImagePaths = [
            'admin/images/settings/favicon.png',
            'admin/images/settings/logo-white.png',
            'admin/images/settings/logo-dark.png',
            'admin/images/settings/tiny-logo.png',
            'admin/images/settings/maintainance.jpg',
        ];

        // Récupérez la collection des fichiers médias
        $attachments = Helpers::createAttachment();

        foreach ($defaultImagePaths as $defaultImagePath) {
            $fullImagePath = public_path($defaultImagePath);

            // Supprimez les médias de la collection
            $mediaItems = $attachments->getMedia('attachment')->filter(function ($media) use ($fullImagePath) {
                return $media->file_name === basename($fullImagePath);
            });

            foreach ($mediaItems as $media) {
                $media->delete();
            }
        }

        // Mettre à jour l'entrée dans la table seeders pour indiquer que ce seeder a été annulé
        DB::table('seeders')->where('name', 'DefaultImagesSeeder')->update([
            'is_completed' => false
        ]);
    }
}
