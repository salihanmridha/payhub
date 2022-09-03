<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            SmartPay
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200 m-auto">
                  <div class="flex justify-center">
                      <div class="mb-3">
                        <form action="{{ route("payment.store") }}" method="post" enctype="multipart/form-data">
                          @csrf
                          <label class="mb-4">Insert CSV for Payment Fee Calculation</label>
                          <input class="form-control
                          block
                          w-full
                          px-3
                          py-1.5
                          text-base
                          font-normal
                          text-gray-700
                          bg-white bg-clip-padding
                          border border-solid border-gray-300
                          rounded
                          transition
                          ease-in-out
                          m-0
                          focus:text-gray-700 focus:bg-white focus:border-blue-600 focus:outline-none" type="file" id="formFile" name="file">
                          @error('file')
                              <div class="text-red-800">{{ $message }}</div>
                          @enderror

                          <x-button class="mt-3">Submit</x-button>

                        </form>
                      </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
