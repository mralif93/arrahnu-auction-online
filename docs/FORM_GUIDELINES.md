# 📋 **FORM TEMPLATE GUIDELINES**

## 🎯 **Overview**

This document outlines the standardized guidelines for all form templates in the admin panel to ensure consistency, usability, and professional appearance across the application.

## 🏗️ **Form Structure Standards**

### **1. Page Layout**
```blade
@extends('layouts.admin')

@section('title', 'Page Title')

@section('content')
    <div class="space-y-6">
        <!-- Header Section -->
        <!-- Form Section -->
        <!-- Help Information Section -->
    </div>
@endsection
```

### **2. Header Section**
```blade
<!-- Header -->
<div class="flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Page Title</h1>
        <p class="text-[#706f6c] dark:text-[#A1A09A]">Page description</p>
    </div>
    <a href="{{ route('back.route') }}"
       class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Back to List
    </a>
</div>
```

### **3. Form Container**
```blade
<!-- Form Container -->
<div class="bg-white dark:bg-[#161615] rounded-lg shadow-sm border border-[#e3e3e0] dark:border-[#3E3E3A]">
    <div class="p-6">
        <form action="{{ route('form.action') }}" method="POST" id="formId">
            @csrf
            <!-- Form Content -->
        </form>
    </div>
</div>
```

## 🎨 **Form Element Standards**

### **1. Input Fields**
```blade
<!-- Text Input -->
<div>
    <label for="field_name" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
        Field Label <span class="text-red-500">*</span>
    </label>
    <input type="text"
           id="field_name"
           name="field_name"
           value="{{ old('field_name') }}"
           class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent"
           placeholder="Enter field value"
           required>
    @error('field_name')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
```

### **2. Select Fields**
```blade
<!-- Select Dropdown -->
<div>
    <label for="select_field" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
        Select Label <span class="text-red-500">*</span>
    </label>
    <select name="select_field"
            id="select_field"
            class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent"
            required>
        <option value="">Select an option</option>
        @foreach($options as $option)
            <option value="{{ $option->id }}" {{ old('select_field') == $option->id ? 'selected' : '' }}>
                {{ $option->name }}
            </option>
        @endforeach
    </select>
    @error('select_field')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
```

### **3. Textarea Fields**
```blade
<!-- Textarea -->
<div>
    <label for="description" class="block text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC] mb-2">
        Description
    </label>
    <textarea id="description"
              name="description"
              rows="4"
              class="w-full px-3 py-2 border border-[#e3e3e0] dark:border-[#3E3E3A] rounded-lg bg-white dark:bg-[#161615] text-[#1b1b18] dark:text-[#EDEDEC] focus:ring-2 focus:ring-brand focus:border-transparent"
              placeholder="Enter description (optional)">{{ old('description') }}</textarea>
    @error('description')
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
```

## 🔘 **Button Standards**

### **1. Primary Action Buttons (FE5000 Pattern)**
```blade
<!-- Primary Submit Button -->
<button type="submit"
        class="px-4 py-2 text-white text-sm font-medium rounded-lg border-0 cursor-pointer"
        style="background-color: #FE5000; position: relative; z-index: 10;"
        onmouseover="this.style.backgroundColor='#E5470A'"
        onmouseout="this.style.backgroundColor='#FE5000'">
    Submit Action
</button>
```

### **2. Secondary Action Buttons**
```blade
<!-- Save as Draft Button -->
<button type="submit"
        name="submit_action"
        value="draft"
        class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
    Save as Draft
</button>
```

### **3. Cancel/Back Buttons**
```blade
<!-- Cancel Button -->
<a href="{{ route('back.route') }}"
   class="px-4 py-2 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-sm font-medium rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors">
    Cancel
</a>
```

## 📝 **Form Actions Section**

### **1. Create Forms (Draft + Submit Pattern)**
```blade
<!-- Submit Actions -->
<div class="flex items-center justify-between pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
    <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
        <span class="text-red-500">*</span> Required fields
    </div>

    <div class="flex items-center space-x-3">
        <!-- Save as Draft -->
        <button type="submit"
                name="submit_action"
                value="draft"
                class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
            Save as Draft
        </button>

        <!-- Submit for Approval -->
        <button type="submit"
                name="submit_action"
                value="submit_for_approval"
                class="px-4 py-2 text-white text-sm font-medium rounded-lg border-0 cursor-pointer"
                style="background-color: #FE5000; position: relative; z-index: 10;"
                onmouseover="this.style.backgroundColor='#E5470A'"
                onmouseout="this.style.backgroundColor='#FE5000'">
            Submit for Approval
        </button>
    </div>
</div>
```

### **2. Edit Forms (Conditional Actions)**
```blade
<!-- Submit Actions -->
<div class="flex items-center justify-between pt-6 border-t border-[#e3e3e0] dark:border-[#3E3E3A]">
    <div class="text-sm text-[#706f6c] dark:text-[#A1A09A]">
        <span class="text-red-500">*</span> Required fields
    </div>

    <div class="flex items-center space-x-3">
        @if($item->status === 'draft')
            <!-- Draft Actions -->
            <button type="submit" name="submit_action" value="draft" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg transition-colors">
                Save as Draft
            </button>
            <button type="submit" name="submit_action" value="submit_for_approval" class="px-4 py-2 text-white text-sm font-medium rounded-lg border-0 cursor-pointer" style="background-color: #FE5000; position: relative; z-index: 10;" onmouseover="this.style.backgroundColor='#E5470A'" onmouseout="this.style.backgroundColor='#FE5000'">
                Submit for Approval
            </button>
        @else
            <!-- Update Action -->
            <button type="submit" class="px-4 py-2 text-white text-sm font-medium rounded-lg border-0 cursor-pointer" style="background-color: #FE5000; position: relative; z-index: 10;" onmouseover="this.style.backgroundColor='#E5470A'" onmouseout="this.style.backgroundColor='#FE5000'">
                Update Item
            </button>
        @endif
    </div>
</div>
```

## 📚 **Help Information Section**

### **Standard Help Section Template**
```blade
<!-- Help Information -->
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                Form Guidelines
            </h3>
            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Field 1:</strong> Description of field requirements</li>
                    <li><strong>Field 2:</strong> Description of field requirements</li>
                    <!-- Add more guidelines as needed -->
                </ul>
            </div>
        </div>
    </div>
</div>
```

## 📋 **Module-Specific Guidelines**

### **1. User Forms**
```blade
<!-- User Creation Guidelines -->
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                User Management Guidelines
            </h3>
            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Full Name:</strong> Enter complete name for proper identification</li>
                    <li><strong>Email:</strong> Must be unique and valid email address</li>
                    <li><strong>Phone:</strong> Use international format (e.g., +603-2141-8888)</li>
                    <li><strong>Admin Role:</strong> Grant admin privileges carefully</li>
                    <li><strong>Password:</strong> Minimum 8 characters with mixed case and numbers</li>
                </ul>
            </div>
        </div>
    </div>
</div>
```

### **2. Branch Forms**
```blade
<!-- Branch Creation Guidelines -->
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                Branch Creation Guidelines
            </h3>
            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Draft:</strong> Save the branch for later editing before submission</li>
                    <li><strong>Submit for Approval:</strong> Send the branch to checkers for approval</li>
                    <li><strong>Branch Name:</strong> Must be unique across all branches</li>
                    <li><strong>Address:</strong> Provide complete address for accurate location</li>
                    <li><strong>Phone:</strong> Use international format (e.g., +603-2141-8888)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
```

### **3. Account Forms**
```blade
<!-- Account Creation Guidelines -->
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                Account Creation Guidelines
            </h3>
            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Draft:</strong> Save the account for later editing before submission</li>
                    <li><strong>Submit for Approval:</strong> Send the account to checkers for approval</li>
                    <li><strong>Branch:</strong> Select the branch where this account will be managed</li>
                    <li><strong>Account Title:</strong> Use a clear, descriptive name for easy identification</li>
                    <li><strong>Description:</strong> Add notes or details about the account (optional)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
```

### **4. Auction Forms**
```blade
<!-- Auction Creation Guidelines -->
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                Auction Creation Guidelines
            </h3>
            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Auction Title:</strong> Use clear, descriptive titles that identify the auction purpose</li>
                    <li><strong>Branch Selection:</strong> Choose the branch where the auction will be conducted</li>
                    <li><strong>Start Date:</strong> Set the auction start date and time (must be in the future)</li>
                    <li><strong>End Date:</strong> End date will auto-populate to 7 days after start date</li>
                    <li><strong>Duration:</strong> Ensure adequate time for bidders to participate</li>
                    <li><strong>Description:</strong> Provide additional details about the auction terms and conditions</li>
                </ul>
            </div>
        </div>
    </div>
</div>
```

### **5. Collateral Forms**
```blade
<!-- Collateral Creation Guidelines -->
<div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4">
    <div class="flex">
        <div class="flex-shrink-0">
            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
            </svg>
        </div>
        <div class="ml-3">
            <h3 class="text-sm font-medium text-blue-800 dark:text-blue-200">
                Collateral Creation Guidelines
            </h3>
            <div class="mt-2 text-sm text-blue-700 dark:text-blue-300">
                <ul class="list-disc list-inside space-y-1">
                    <li><strong>Draft:</strong> Save the collateral for later editing before submission</li>
                    <li><strong>Submit for Approval:</strong> Send the collateral to checkers for approval</li>
                    <li><strong>Item Type:</strong> Be specific about the type of collateral (e.g., Gold Ring, Diamond Necklace)</li>
                    <li><strong>Description:</strong> Provide detailed description including condition and special features</li>
                    <li><strong>Images:</strong> Upload clear photos from multiple angles (max 5 images, 2MB each)</li>
                    <li><strong>Starting Bid:</strong> Set a reasonable starting bid amount in RM</li>
                </ul>
            </div>
        </div>
    </div>
</div>
```

## 🎨 **Color Standards**

### **1. FE5000 Color Palette**
- **Primary**: `#FE5000` (Base orange-red)
- **Primary Hover**: `#E5470A` (Darker orange-red)
- **Secondary**: `#6B7280` (Gray for draft actions)
- **Secondary Hover**: `#4B5563` (Darker gray)

### **2. Status Colors**
- **Success/Active**: `bg-green-100 text-green-800` (Light green)
- **Warning/Pending**: `bg-yellow-100 text-yellow-800` (Light yellow)
- **Error/Rejected**: `bg-red-100 text-red-800` (Light red)
- **Neutral/Draft**: `bg-gray-100 text-gray-800` (Light gray)

## 📱 **Responsive Design**

### **1. Grid Layout**
```blade
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Form fields arranged in responsive grid -->
</div>
```

### **2. Mobile-First Approach**
- Start with single column layout
- Use `md:` prefix for tablet and desktop layouts
- Ensure touch-friendly button sizes (minimum 44px)

## ✅ **Validation Standards**

### **1. Required Field Indicators**
```blade
<span class="text-red-500">*</span>
```

### **2. Error Display**
```blade
@error('field_name')
    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
@enderror
```

### **3. Success Messages**
```blade
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
        {{ session('success') }}
    </div>
@endif
```

## 🔧 **JavaScript Standards**

### **1. Form Validation**
```javascript
// Client-side validation example
document.getElementById('formId').addEventListener('submit', function(e) {
    // Add validation logic
});
```

### **2. Dynamic Field Updates**
```javascript
// Auto-populate related fields
document.getElementById('field1').addEventListener('change', function() {
    // Update dependent fields
});
```

## 📋 **Checklist for Form Implementation**

### **✅ Required Elements**
- [ ] Proper page title and description
- [ ] Back navigation button
- [ ] Form container with proper styling
- [ ] Required field indicators
- [ ] Error handling for all fields
- [ ] Consistent button styling (FE5000 pattern)
- [ ] Help information section
- [ ] Responsive design
- [ ] Proper form validation
- [ ] Loading states for submissions

### **✅ Optional Enhancements**
- [ ] Auto-save functionality
- [ ] Field dependencies
- [ ] Progress indicators
- [ ] File upload previews
- [ ] Confirmation dialogs
- [ ] Keyboard shortcuts

## 🎯 **Best Practices**

1. **Consistency**: Use the same patterns across all forms
2. **Accessibility**: Ensure proper labels and ARIA attributes
3. **Performance**: Minimize JavaScript and optimize images
4. **User Experience**: Provide clear feedback and guidance
5. **Security**: Always validate on both client and server side
6. **Maintainability**: Follow established patterns and conventions
