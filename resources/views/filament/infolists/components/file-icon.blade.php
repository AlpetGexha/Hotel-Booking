@php
    $isPdf = $extension === 'pdf';
    $isDoc = in_array($extension, ['doc', 'docx']);
    $isSpreadsheet = in_array($extension, ['xls', 'xlsx', 'csv']);
    $isArchive = in_array($extension, ['zip', 'rar', '7z', 'tar', 'gz']);
    $isAudio = in_array($extension, ['mp3', 'wav', 'ogg', 'flac']);
    $isVideo = in_array($extension, ['mp4', 'mov', 'avi', 'wmv']);

    $bgColor = match(true) {
        $isPdf => 'bg-red-100 text-red-600',
        $isDoc => 'bg-blue-100 text-blue-600',
        $isSpreadsheet => 'bg-green-100 text-green-600',
        $isArchive => 'bg-amber-100 text-amber-600',
        $isAudio => 'bg-purple-100 text-purple-600',
        $isVideo => 'bg-pink-100 text-pink-600',
        default => 'bg-gray-100 text-gray-600',
    };

    $icon = match(true) {
        $isPdf => 'heroicon-o-document-text',
        $isDoc => 'heroicon-o-document',
        $isSpreadsheet => 'heroicon-o-table-cells',
        $isArchive => 'heroicon-o-archive-box',
        $isAudio => 'heroicon-o-musical-note',
        $isVideo => 'heroicon-o-film',
        default => 'heroicon-o-document',
    };
@endphp

<div class="flex items-center justify-center h-32 w-full">
    <div class="flex flex-col items-center justify-center rounded-md border-2 border-dashed p-6 {{ $bgColor }} bg-opacity-40 border-opacity-40 w-32 h-32">
        @svg($icon, 'w-12 h-12')
        <span class="mt-2 text-xs font-medium uppercase">{{ strtoupper($extension) }}</span>
    </div>
</div>
