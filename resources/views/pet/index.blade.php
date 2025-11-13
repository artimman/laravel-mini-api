@extends('base')

@section('title', 'Pet Management')

@section('stylesheet')
<!-- Stylesheet.index -->
    <style>
        #fieldPetId { display: none; }
    </style>  
@endsection

@section('content')
<div class="bg-white p-6 rounded-lg shadow-lg w-96">
            <div class="flex justify-between">
                <h2 class="text-xl font-bold mb-4">Manage Pets</h2>
                <span id="currentPetContainer" class="hidden text-sm text-gray-600">Pet ID: <span id="currentPetId"></span></span>
            </div>
            <form id="formPet">
                <div id="formMessage" class="p-2 mb-2 rounded hidden"></div>
                <input type="number" id="fieldPetId" class="w-full p-2 mb-2 border rounded" placeholder="Enter Pet ID" min="0">
                <input type="text" id="fieldName" class="w-full p-2 mb-2 border rounded" placeholder="Pet Name">
                <input type="number" id="fieldCategoryId" class="w-full p-2 mb-2 border rounded" placeholder="Category ID" min="0">
                <input type="text" id="fieldCategoryName" class="w-full p-2 mb-2 border rounded" placeholder="Category Name">
                <input type="text" id="fieldPhotoUrls" class="w-full p-2 mb-2 border rounded" placeholder="Photo URLs (comma-separated)">
                <input type="text" id="fieldTags" class="w-full p-2 mb-2 border rounded" placeholder="Tags (comma-separated)">
                <select id="fieldStatus" class="w-full p-2 mb-4 border rounded">
                    <option value="available">Available</option>
                    <option value="pending">Pending</option>
                    <option value="sold">Sold</option>
                </select>
                <button type="submit" class="w-full bg-blue-500 text-white p-2 rounded">Add Pet</button>
            </form>
            <button id="getPetBtn" type="button" class="w-full bg-green-500 text-white p-2 rounded mt-2">Get Pet</button>
            <button id="updatePetBtn" type="button" class="w-full bg-yellow-500 text-white p-2 rounded mt-2">Update Pet</button>
            <button id="deletePetBtn" type="button" class="w-full bg-red-500 text-white p-2 rounded mt-2">Delete Pet</button>
            <button id="clearFormBtn" type="button" class="w-full bg-gray-500 text-white p-2 rounded mt-2">Clear</button>
        </div>
@endsection

@section('javascript')
<!-- JavaScript.index -->
    <script src="{{ asset('assets/js/pet.js') }}"></script>
@endsection
