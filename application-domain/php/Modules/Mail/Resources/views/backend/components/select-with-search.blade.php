<x-field-wrapper :name="$name" :label="$label">
    <div class="relative w-full group">
        <button type="button"
                id="dropdown-button-{{ $name }}"
                class="inline-flex justify-between px-4 py-2 w-full text-sm font-medium text-gray-700 bg-white rounded-md border border-gray-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-100 focus:ring-blue-500">
            <span class="mr-2" id="selected-value-{{ $name }}">
                {{ $attributes->get('placeholder', 'Select Option') }}
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" class="-mr-1 ml-2 w-5 h-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>

        <div id="dropdown-menu-{{ $name }}"
             class="hidden absolute z-10 p-1 mt-2 space-y-1 w-full bg-white rounded-md ring-1 ring-black ring-opacity-5 shadow-lg">
            <input id="search-input-{{ $name }}"
                   class="block px-4 py-2 w-full text-gray-800 rounded-md border border-gray-300 focus:outline-none"
                   type="text"
                   placeholder="Search items"
                   autocomplete="off">

            <div class="overflow-auto max-h-60">
                @foreach($options as $key => $text)
                    <a href="#"
                       data-value="{{ $key }}"
                       class="block px-4 py-2 text-gray-700 rounded-md cursor-pointer hover:bg-gray-100 active:bg-blue-100">
                        {{ $text }}
                    </a>
                @endforeach
            </div>
        </div>

        <select name="{{ $name }}"
                {{ $attributes->merge(['id' => 'id-field-' . str_replace('[]', '', $name), 'class' => 'hidden ' . ($multiple ? 'selectpicker' : '')]) }}
                {{ $multiple ? 'multiple' : '' }}>
            @foreach($options as $key => $text)
                <option value="{{ $key }}" {{ $isSelected($key) ? 'selected' : '' }}>{{ $text }}</option>
            @endforeach
        </select>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownButton = document.getElementById('dropdown-button-{{ $name }}');
            const dropdownMenu = document.getElementById('dropdown-menu-{{ $name }}');
            const searchInput = document.getElementById('search-input-{{ $name }}');
            const selectElement = document.getElementById('id-field-{{ str_replace("[]", "", $name) }}');
            const selectedValueSpan = document.getElementById('selected-value-{{ $name }}');
            let isOpen = false;

            function updateSelectedValue() {
                const selectedOption = selectElement.options[selectElement.selectedIndex];
                if (selectedOption) {
                    selectedValueSpan.textContent = selectedOption.text;
                }
            }

            function toggleDropdown() {
                isOpen = !isOpen;
                dropdownMenu.classList.toggle('hidden', !isOpen);
                if (isOpen) {
                    searchInput.focus();
                }
            }

            // Initial selected value
            updateSelectedValue();

            // Click outside to close
            document.addEventListener('click', (e) => {
                if (!dropdownButton.contains(e.target) && !dropdownMenu.contains(e.target)) {
                    isOpen = false;
                    dropdownMenu.classList.add('hidden');
                }
            });

            dropdownButton.addEventListener('click', (e) => {
                e.preventDefault();
                toggleDropdown();
            });

            // Handle dropdown item selection
            dropdownMenu.querySelectorAll('a').forEach(item => {
                item.addEventListener('click', (e) => {
                    e.preventDefault();
                    const value = item.dataset.value;
                    selectElement.value = value;
                    selectedValueSpan.textContent = item.textContent;
                    toggleDropdown();

                    // Trigger change event on select
                    selectElement.dispatchEvent(new Event('change', { bubbles: true }));
                });
            });

            // Filter items based on search
            searchInput.addEventListener('input', () => {
                const searchTerm = searchInput.value.toLowerCase();
                dropdownMenu.querySelectorAll('a').forEach(item => {
                    const text = item.textContent.toLowerCase();
                    item.style.display = text.includes(searchTerm) ? 'block' : 'none';
                });
            });

            // Prevent search input from triggering dropdown close
            searchInput.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });
    </script>
    @endpush
</x-field-wrapper>
