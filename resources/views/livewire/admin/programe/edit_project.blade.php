<div class="p-8 bg-gray-50 min-h-screen">



    <form class="bg-white rounded-xl shadow-sm p-8 ">

        <!-- SECTION 1: Project Settings -->
        <div class="mb-10">
            <h2 class="text-lg font-medium text-gray-800 mb-4">
                Project Settings
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project Name -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Project Name *
                    </label>
                    <input type="text"
                           required
                           placeholder="e.g. Scholarship Application"
                           class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Status -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Status
                    </label>
                    <select class="w-full px-4 py-2 border rounded-lg">
                        <option>Active</option>
                        <option>Inactive</option>
                    </select>
                </div>
            </div>

            <!-- Description -->
            <div class="mt-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Description *
                </label>
                <textarea
                    required
                    rows="3"
                    placeholder="Explain the purpose of this form..."
                    class="w-full px-4 py-2 border rounded-lg"></textarea>
            </div>
        </div>

        <!-- SECTION 2: Access Rules -->
        <div class="mb-10">
            <h2 class="text-lg font-medium text-gray-800 mb-4">
                User Access Rules
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Min Age -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Minimum Age *
                    </label>
                    <input type="number"
                           min="0"
                           placeholder="e.g. 18"
                           required
                           class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Max Age -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Maximum Age *
                    </label>
                    <input type="number"
                           min="0"
                           placeholder="e.g. 35"
                           required
                           class="w-full px-4 py-2 border rounded-lg">
                </div>

                <!-- Address Source -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Address Source
                    </label>
                    <select class="w-full px-4 py-2 border rounded-lg">
                        <option>From backend address list</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- SECTION 3: Form Fields (Champs) -->
        <div class="mb-10">
            <h2 class="text-lg font-medium text-gray-800 mb-4">
                Form Fields (User Inputs)
            </h2>

            <!-- Field Row -->
            <div class="border rounded-lg p-4 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <input type="text"
                           placeholder="Field Label (e.g. Full Name)"
                           class="px-3 py-2 border rounded">

                    <select class="px-3 py-2 border rounded">
                        <option>Text</option>
                        <option>Number</option>
                        <option>Email</option>
                        <option>Textarea</option>
                        <option>Select</option>
                        <option>Address Select</option>
                    </select>

                    <select class="px-3 py-2 border rounded">
                        <option>Optional</option>
                        <option>Required</option>
                    </select>

                    <button type="button"
                            class="px-3 py-2 text-red-600 hover:underline">
                        Remove
                    </button>
                </div>
            </div>

            <!-- Add Field Button -->
            <button type="button"
                    class="inline-flex items-center gap-2 px-4 py-2 border rounded-lg hover:bg-gray-100">
                + Add Field
            </button>

            <p class="text-xs text-gray-500 mt-2">
                Address Select will use values from backend address list.
            </p>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-4">
            <button type="button"
                    class="px-5 py-2.5 border rounded-lg text-gray-600 hover:bg-gray-100">
                Cancel
            </button>

            <button type="submit"
                    class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Save Project
            </button>
        </div>

    </form>
</div>
