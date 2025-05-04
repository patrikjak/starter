<?php

declare(strict_types = 1);

namespace Patrikjak\Starter\Services\Authors;

use Exception;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Patrikjak\Starter\Models\Authors\Author;
use Patrikjak\Starter\Repositories\Contracts\Authors\AuthorRepository;

readonly class AuthorService
{
    public function __construct(private AuthorRepository $authorRepository, private FilesystemManager $filesystem)
    {
    }

    public function getProfilePicturePath(Author $author): string
    {
        return asset(sprintf('storage/%s', $author->profile_picture));
    }

    /**
     * @throws Exception
     */
    public function saveAuthor(string $name, ?UploadedFile $profilePicture): void
    {
        $path = null;

        if ($profilePicture !== null) {
            $path = $this->saveProfilePicture($profilePicture);
        }

        try {
            $this->authorRepository->create($name, $path);
        } catch (Exception $e) {
            if ($path !== null) {
                $this->filesystem->disk('public')->delete($path);
            }

            throw $e;
        }
    }

    public function updateAuthor(
        Author $author,
        string $newName,
        ?UploadedFile $newProfilePicture,
        Collection $filesToDelete,
    ): void {
        $profilePicturePath = null;
        $removeProfilePicture = $filesToDelete->isNotEmpty();

        if ($newProfilePicture !== null) {
            if ($author->profile_picture !== null) {
                $this->filesystem->disk('public')->delete($author->profile_picture);
            }

            $profilePicturePath = $this->saveProfilePicture($newProfilePicture);
        }

        if ($removeProfilePicture && $newProfilePicture === null) {
            $removeCurrentProfilePicture = $filesToDelete->contains(basename($author->profile_picture));

            if ($removeCurrentProfilePicture) {
                $this->filesystem->disk('public')->delete($author->profile_picture);
            }
        }

        if (!$removeProfilePicture && $newProfilePicture === null) {
            $profilePicturePath = $author->profile_picture;
        }

        if (!$removeProfilePicture && $newProfilePicture !== null) {
            $profilePicturePath = $this->saveProfilePicture($newProfilePicture);
        }

        $this->authorRepository->update($author->id, $newName, $profilePicturePath);
    }

    public function deleteAuthor(Author $author): void
    {
        if ($author->profile_picture !== null) {
            $this->filesystem->disk('public')->delete($author->profile_picture);
        }

        $this->authorRepository->delete($author->id);
    }

    public function getAllAsOptions(): Collection
    {
        return $this->authorRepository->getAll()
            ->mapwithkeys(static fn (Author $item) => [$item->id => $item->name]);
    }

    private function saveProfilePicture(UploadedFile $profilePicture): string
    {
        return $profilePicture->store('profile_pictures', 'public');
    }
}