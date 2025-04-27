@php
    $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    $isPdf = $extension === 'pdf';
    $isDoc = in_array($extension, ['doc', 'docx']);
@endphp

<div class="flex flex-col h-full justify-center p-4 @if ($isImage) pl-0 @endif">
    <div class="flex items-center gap-4 mb-2">
        @if (!$isImage)
            <div class="shrink-0">
                @if ($isPdf)
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-red-100 text-red-500">
                        <x-heroicon-o-document-text class="w-5 h-5" />
                    </div>
                @elseif ($isDoc)
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-100 text-blue-500">
                        <x-heroicon-o-document-text class="w-5 h-5" />
                    </div>
                @else
                    <div class="w-10 h-10 flex items-center justify-center rounded-full bg-gray-100 text-gray-500">
                        <x-heroicon-o-document class="w-5 h-5" />
                    </div>
                @endif
            </div>
        @endif

        <div class="flex-1 truncate">
            <h3 class="text-sm font-medium text-gray-900 dark:text-white truncate">
                {{ $media->file_name }}
            </h3>
            <p class="text-xs text-gray-500 dark:text-gray-400">
                {{ strtoupper($extension) }} • {{ $fileSize }}
            </p>
        </div>
    </div>

    <div class="mt-2">
        <a
            href="{{ $media->getUrl() }}"
            target="_blank"
            class="inline-flex items-center px-3 py-1.5 text-xs font-medium rounded-md bg-primary-50 text-primary-600 hover:bg-primary-100 dark:bg-primary-950 dark:text-primary-400 dark:hover:bg-primary-900"
        >
            <x-heroicon-m-arrow-down-tray class="mr-1 h-4 w-4" />
            Download
        </a>
    </div>
</div>
