@extends('layouts.app')

@section('title', 'Book Details')

@section('content')

    <!-- Tailwind CSS CDN -->

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Custom font import for a clean, modern look */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

    <div class="container mx-auto p-4 md:p-8">
        <div class="bg-white rounded-xl shadow-lg p-6 md:p-10 transition-all duration-300">
            <div class="border-b border-gray-200 pb-4 mb-6">
                <h2 class="text-3xl font-bold text-gray-800">📖 Book Details</h2>
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center gap-8">
                <!-- Book Image -->
                <div class="flex-shrink-0 flex justify-center w-full md:w-auto">
                    @if ($book->image)
                        <img src="{{ asset('storage/' . $book->image) }}" alt="Book Cover"
                            class="w-48 h-64 object-cover rounded-lg shadow-md border border-gray-200 transition-transform duration-300 hover:scale-105" />
                    @else
                        <img src="https://placehold.co/200x260/E5E7EB/4B5563?text=No+Image" alt="Book Cover Placeholder"
                            class="w-48 h-64 object-cover rounded-lg shadow-md border border-gray-200" />
                    @endif
                </div>

                <!-- Book Details -->
                <div class="flex-grow text-gray-700 space-y-3">
                    <p><strong>📖 Title:</strong> <span class="text-gray-900">{{ $book->title }}</span></p>
                    <p><strong>✍️ Author:</strong> <span class="text-gray-900">{{ $book->author }}</span></p>
                    <p><strong>📂 Category:</strong> <span class="text-gray-900">{{ $book->category->name }}</span></p>
                    <p><strong>🏷️ ISBN:</strong> <span class="text-gray-900">{{ $book->isbn }}</span></p>
                    <p><strong>📚 Total Copies:</strong> <span class="text-gray-900">{{ $book->total_copies }}</span></p>
                    <p><strong>✅ Available Copies:</strong> <span class="text-gray-900">{{ $book->available_copies }}</span>
                    </p>

                    @if ($book->shelf)
                        <p><strong>➡️ Row Number:</strong> <span class="text-gray-900">{{ $book->shelf->row_number }}</span>
                        </p>
                        <p><strong>⬇️ Sub Col Number:</strong> <span
                                class="text-gray-900">{{ $book->shelf->sub_col_number }}</span></p>
                    @else
                        <p><strong>⚠️ Location:</strong> <span class="text-red-500 font-medium">Not assigned to a
                                shelf</span></p>
                    @endif

                    @if ($book->barcode)
                        <div class="mt-6">
                            <h5 class="font-semibold text-lg text-gray-800 mb-2">📊 Barcode:</h5>
                            <div
                                class="bg-gray-100 p-4 rounded-lg border border-gray-200 inline-block transition-transform duration-300 hover:scale-105">
                                {!! $book->barcode !!}
                            </div>
                        </div>
                    @endif
                </div>

            </div>

            <!-- Success Message Container -->

            <div id="success-message"
                class="hidden fixed bottom-5 right-5 z-50 p-4 rounded-lg shadow-xl transition-all duration-300 transform scale-95 opacity-0">
                <div class="flex items-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-500" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2l4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span class="text-sm font-medium text-gray-800">Files successfully exported!</span>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col md:flex-row gap-4">
                <a href="{{ route('admin.books.index') }}"
                    class="inline-block px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg shadow-md hover:bg-gray-600 transition-colors duration-300 text-center">
                    ⬅️ Back to Book List
                </a>
                <!-- Updated button to call the JavaScript function -->
                <button
                    onclick="downloadAndNotify('{{ route('admin.books.exportPdf', $book->id) }}', '{{ route('admin.books.exportBarcode', $book->id) }}')"
                    class="inline-block px-6 py-3 bg-red-500 text-white font-semibold rounded-lg shadow-md hover:bg-red-600 transition-colors duration-300 text-center">
                    📄 Export All
                </button>
            </div>

        </div>

    </div>

    <script>
  function downloadAndNotify(pdfUrl, barcodeUrl) {
    const message = document.getElementById('success-message');
    message.classList.remove('hidden', 'scale-95', 'opacity-0');
    message.classList.add('bg-white', 'text-gray-800', 'scale-100', 'opacity-100');
    message.querySelector('span').textContent = 'Files successfully exported!';

    // Create and click a temporary link for the PDF
    const pdfLink = document.createElement('a');
    pdfLink.href = pdfUrl;
    pdfLink.download = 'book_details.pdf';
    document.body.appendChild(pdfLink);
    pdfLink.click();
    document.body.removeChild(pdfLink);

    // Create and click a temporary link for the barcode with a slight delay
    setTimeout(() => {
      const barcodeLink = document.createElement('a');
      barcodeLink.href = barcodeUrl;
      barcodeLink.download = 'book_barcode.svg';
      document.body.appendChild(barcodeLink);
      barcodeLink.click();
      document.body.removeChild(barcodeLink);
    }, 500); // 500ms delay to prevent browsers from blocking the second download

    setTimeout(() => {
      message.classList.add('scale-95', 'opacity-0');
      message.classList.remove('scale-100', 'opacity-100');

      setTimeout(() => {
        message.classList.add('hidden');
      }, 300); // Wait for the transition to finish
    }, 3000); // Hide after 3 seconds
  }
</script>


@endsection
