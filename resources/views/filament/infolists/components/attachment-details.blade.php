<div class="p-4 rounded-md border border-gray-200 dark:border-gray-700 h-full">
    <div class="space-y-3">
        <div>
            <h3 class="text-sm font-medium text-gray-900 dark:text-white break-all">
                {{ $media->file_name }}
            </h3>
            <div class="mt-1 flex items-center text-xs text-gray-500 dark:text-gray-400">
                <span class="font-medium mr-2">{{ strtoupper($extension) }}</span>
                <span class="mx-1">•</span>
                <span>{{ $fileSize }}</span>
                <span class="mx-1">•</span>
                <span>{{ $media->created_at->format('M d, Y') }}</span>
            </div>
        </div>

        <div class="flex space-x-2">
            <a
                href="{{ $media->getUrl() }}"
                target="_blank"
                class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded bg-primary-50 text-primary-600 hover:bg-primary-100 dark:bg-primary-950 dark:text-primary-400 dark:hover:bg-primary-900 transition"
            >
                @svg('heroicon-m-arrow-down-tray', 'h-3.5 w-3.5 mr-1')
                Download
            </a>

            @php
                $isPdf = $extension === 'pdf';
                $isDoc = in_array($extension, ['doc', 'docx']);
                $isViewable = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf']);
            @endphp

            @if($isViewable)
                <a
                    href="{{ $media->getUrl() }}"
                    target="_blank"
                    class="inline-flex items-center px-2.5 py-1.5 text-xs font-medium rounded bg-gray-50 text-gray-600 hover:bg-gray-100 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-gray-700 transition"
                >
                    @svg('heroicon-m-eye', 'h-3.5 w-3.5 mr-1')
                    View
                </a>
            @endif
        </div>
    </div>
</div>
