<div class="max-w-3xl mx-auto">
    @if ($success)
        <div class="bg-green-50 border border-green-200 rounded-md p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-green-800">Success!</h3>
                    <div class="mt-2 text-sm text-green-700">
                        <p>Your message has been sent successfully. We'll get back to you shortly.</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @error('rate_limit')
        <div class="bg-red-50 border border-red-200 rounded-md p-4 mb-8">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Rate limit exceeded</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>{{ $message }}</p>
                    </div>
                </div>
            </div>
        </div>
    @enderror

    <form wire:submit="submit" class="bg-white dark:bg-slate-800 shadow-md rounded-lg overflow-hidden px-6 py-8">
        <h2 class="text-2xl font-bold text-slate-900 dark:text-white mb-6">Contact Us</h2>
        <p class="text-slate-600 dark:text-slate-400 mb-6">Have a question or feedback? Fill out the form below and we'll get back to you as soon as possible.</p>

        <div class="space-y-6">
            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Email Address
                </label>
                <input
                    wire:model="email"
                    type="email"
                    id="email"
                    class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="your@email.com"
                >
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Subject Field -->
            <div>
                <label for="subject" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Subject
                </label>
                <input
                    wire:model="subject"
                    type="text"
                    id="subject"
                    class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="What's this about?"
                >
                @error('subject')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message Field -->
            <div>
                <label for="message" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Message
                </label>
                <textarea
                    wire:model="message"
                    id="message"
                    rows="5"
                    class="block w-full rounded-md border-slate-300 dark:border-slate-600 dark:bg-slate-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    placeholder="Your message here..."
                ></textarea>
                @error('message')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Multiple File Attachments -->
            <div>
                <label for="attachments" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                    Attachments (Optional, max 5 files)
                </label>
                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col rounded-lg border-2 border-dashed w-full h-32 p-10 group text-center border-slate-300 dark:border-slate-600 hover:bg-slate-50 dark:hover:bg-slate-700/30">
                        <div class="h-full w-full text-center flex flex-col items-center justify-center">
                            <div wire:loading.remove wire:target="attachments">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-slate-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <p class="text-sm text-slate-500 dark:text-slate-400">
                                    Drag and drop files here or <span class="text-indigo-500">browse</span>
                                </p>
                            </div>
                            <div wire:loading wire:target="attachments" class="text-sm text-slate-500 dark:text-slate-400">
                                Uploading...
                            </div>
                        </div>
                        <input type="file" wire:model="attachments" class="hidden" multiple id="attachments" />
                    </label>
                </div>

                <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">
                    Allowed file types: PDF, DOC, DOCX, JPG, JPEG, PNG (max 10MB per file)
                </p>

                @error('attachments')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                @error('attachments.*')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <!-- File Preview -->
                @if(count($attachments) > 0)
                    <div class="mt-4 space-y-2">
                        <h4 class="text-sm font-medium text-slate-700 dark:text-slate-300">Selected Files:</h4>

                        <ul class="divide-y divide-slate-200 dark:divide-slate-700 border border-slate-200 dark:border-slate-700 rounded-md overflow-hidden">
                            @foreach($attachments as $index => $file)
                                <li class="px-4 py-3 bg-white dark:bg-slate-800 flex items-center justify-between">
                                    <div class="flex items-center space-x-3 truncate">
                                        @php
                                            $extension = strtolower($file->getClientOriginalExtension());
                                            $isImage = in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                                            $isPdf = $extension === 'pdf';
                                            $isDoc = in_array($extension, ['doc', 'docx']);
                                        @endphp

                                        <span class="flex-shrink-0">
                                            @if($isImage)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            @elseif($isPdf)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            @elseif($isDoc)
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                </svg>
                                            @endif
                                        </span>

                                        <span class="text-sm text-slate-900 dark:text-slate-200 truncate">
                                            {{ $file->getClientOriginalName() }}
                                        </span>

                                        <span class="text-xs text-slate-500 dark:text-slate-400">
                                            {{ number_format($file->getSize() / 1024, 0) }} KB
                                        </span>
                                    </div>

                                    <button
                                        type="button"
                                        wire:click="$set('attachments.{{ $index }}', null)"
                                        class="text-red-500 hover:text-red-700 focus:outline-none"
                                        title="Remove file"
                                    >
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                        </svg>
                                    </button>
                                </li>
                            @endforeach
                        </ul>

                        <div class="flex justify-between items-center text-sm">
                            <span class="text-slate-700 dark:text-slate-300">
                                {{ count($attachments) }} of 5 files selected
                            </span>

                            @if(count($attachments) > 0)
                                <button
                                    type="button"
                                    wire:click="$set('attachments', [])"
                                    class="text-red-600 hover:text-red-800 focus:outline-none text-sm"
                                >
                                    Clear all files
                                </button>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Submit Button -->
            <div>
                <button
                    type="submit"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    wire:loading.attr="disabled"
                    wire:loading.class="opacity-75"
                >
                    <span wire:loading.remove wire:target="submit">Send Message</span>
                    <span wire:loading wire:target="submit">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                </button>
            </div>
        </div>
    </form>
</div>
