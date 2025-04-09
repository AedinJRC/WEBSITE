<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Car Maintenance Checklist</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8 max-w-2xl">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h1 class="text-2xl font-bold text-gray-800 mb-6">Car Maintenance Checklist</h1>
            
            <div class="space-y-4">
                <!-- Engine Section -->
                <div class="border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Engine</h2>
                    <div class="space-y-2">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Check engine oil level and condition</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Replace engine oil and filter</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Inspect belts for wear and tension</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Check coolant level and condition</span>
                        </label>
                    </div>
                </div>
                
                <!-- Tires Section -->
                <div class="border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Tires</h2>
                    <div class="space-y-2">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Check tire pressure (including spare)</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Inspect tires for wear and damage</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Rotate tires</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Check wheel alignment</span>
                        </label>
                    </div>
                </div>
                
                <!-- Brakes Section -->
                <div class="border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Brakes</h2>
                    <div class="space-y-2">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Inspect brake pads/shoes</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Check brake fluid level</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Inspect brake lines and hoses</span>
                        </label>
                    </div>
                </div>
                
                <!-- Lights & Electrical Section -->
                <div class="border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Lights & Electrical</h2>
                    <div class="space-y-2">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Check all exterior lights</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Test horn</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Check battery terminals and charge</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Inspect windshield wipers and fluid</span>
                        </label>
                    </div>
                </div>
                
                <!-- Additional Maintenance -->
                <div class="pb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-3">Additional Maintenance</h2>
                    <div class="space-y-2">
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Replace cabin air filter</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Inspect suspension components</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Check exhaust system</span>
                        </label>
                        <label class="flex items-center space-x-3">
                            <input type="checkbox" class="form-checkbox h-5 w-5 text-blue-600 rounded">
                            <span class="text-gray-700">Lubricate door hinges and locks</span>
                        </label>
                    </div>
                </div>
            </div>
            
            <!-- Notes Section -->
            <div class="mt-6">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Additional Notes</label>
                <textarea id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            
            <!-- Save Button -->
            <div class="mt-6 flex justify-end">
                <button id="saveBtn" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                    Save Checklist
                </button>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('saveBtn').addEventListener('click', function() {
            // Get all checked items
            const checkedItems = Array.from(document.querySelectorAll('input[type="checkbox"]:checked'))
                .map(checkbox => checkbox.nextElementSibling.textContent);
            
            // Get notes
            const notes = document.getElementById('notes').value;
            
            // Create a data object to save (in a real app, you would send this to a server)
            const data = {
                checkedItems,
                notes,
                date: new Date().toISOString()
            };
            
            // For demo purposes, just show an alert
            alert('Checklist saved!\n\nChecked items: ' + checkedItems.length + '\nNotes: ' + (notes || 'None'));
            
            // In a real application, you would:
            // 1. Send data to a backend API
            // 2. Or save to localStorage: localStorage.setItem('carChecklist', JSON.stringify(data));
        });
    </script>
</body>
</html>