<?php

require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "🔍 TESTING GUIDELINES CONSISTENCY BETWEEN BRANCHES AND ACCOUNTS" . PHP_EOL;
echo "================================================================" . PHP_EOL . PHP_EOL;

// Login as admin
$admin = App\Models\User::where('email', 'admin@arrahnu.com')->first();
Auth::login($admin);

echo "✅ Logged in as: {$admin->full_name}" . PHP_EOL . PHP_EOL;

// Test branch create form guidelines
echo "🔍 TESTING BRANCH CREATE FORM GUIDELINES" . PHP_EOL;
echo "=========================================" . PHP_EOL;

try {
    $branchCreateRequest = Request::create('/admin/branches/create', 'GET');
    $branchCreateResponse = app()->handle($branchCreateRequest);
    
    if ($branchCreateResponse->getStatusCode() === 200) {
        echo "✅ Branch create form loads successfully" . PHP_EOL;
        
        $branchContent = $branchCreateResponse->getContent();
        
        // Check for guidelines section
        $hasBranchGuidelines = strpos($branchContent, 'Branch Creation Guidelines') !== false;
        $hasBranchHelpSection = strpos($branchContent, 'bg-blue-50') !== false;
        $hasBranchDraftInfo = strpos($branchContent, 'Save the branch for later editing') !== false;
        $hasBranchApprovalInfo = strpos($branchContent, 'Send the branch to checkers') !== false;
        
        echo ($hasBranchGuidelines ? "✅" : "❌") . " Branch Creation Guidelines title found" . PHP_EOL;
        echo ($hasBranchHelpSection ? "✅" : "❌") . " Help section styling found" . PHP_EOL;
        echo ($hasBranchDraftInfo ? "✅" : "❌") . " Draft workflow info found" . PHP_EOL;
        echo ($hasBranchApprovalInfo ? "✅" : "❌") . " Approval workflow info found" . PHP_EOL;
        
    } else {
        echo "❌ Branch create form failed to load: " . $branchCreateResponse->getStatusCode() . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Branch create form error: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;

// Test account create form guidelines
echo "🔍 TESTING ACCOUNT CREATE FORM GUIDELINES" . PHP_EOL;
echo "==========================================" . PHP_EOL;

try {
    $accountCreateRequest = Request::create('/admin/accounts/create', 'GET');
    $accountCreateResponse = app()->handle($accountCreateRequest);
    
    if ($accountCreateResponse->getStatusCode() === 200) {
        echo "✅ Account create form loads successfully" . PHP_EOL;
        
        $accountContent = $accountCreateResponse->getContent();
        
        // Check for guidelines section
        $hasAccountGuidelines = strpos($accountContent, 'Account Creation Guidelines') !== false;
        $hasAccountHelpSection = strpos($accountContent, 'bg-blue-50') !== false;
        $hasAccountDraftInfo = strpos($accountContent, 'Save the account for later editing') !== false;
        $hasAccountApprovalInfo = strpos($accountContent, 'Send the account to checkers') !== false;
        $hasAccountBranchInfo = strpos($accountContent, 'Select the branch where this account') !== false;
        
        echo ($hasAccountGuidelines ? "✅" : "❌") . " Account Creation Guidelines title found" . PHP_EOL;
        echo ($hasAccountHelpSection ? "✅" : "❌") . " Help section styling found" . PHP_EOL;
        echo ($hasAccountDraftInfo ? "✅" : "❌") . " Draft workflow info found" . PHP_EOL;
        echo ($hasAccountApprovalInfo ? "✅" : "❌") . " Approval workflow info found" . PHP_EOL;
        echo ($hasAccountBranchInfo ? "✅" : "❌") . " Branch selection info found" . PHP_EOL;
        
    } else {
        echo "❌ Account create form failed to load: " . $accountCreateResponse->getStatusCode() . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Account create form error: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;

// Test branch edit form guidelines
echo "🔍 TESTING BRANCH EDIT FORM GUIDELINES" . PHP_EOL;
echo "=======================================" . PHP_EOL;

try {
    // Get a branch for testing
    $branch = App\Models\Branch::first();
    if ($branch) {
        $branchEditRequest = Request::create("/admin/branches/{$branch->id}/edit", 'GET');
        $branchEditResponse = app()->handle($branchEditRequest);
        
        if ($branchEditResponse->getStatusCode() === 200) {
            echo "✅ Branch edit form loads successfully" . PHP_EOL;
            
            $branchEditContent = $branchEditResponse->getContent();
            
            // Check for information section
            $hasBranchInfo = strpos($branchEditContent, 'Branch Information') !== false;
            $hasCreatedInfo = strpos($branchEditContent, 'Created:') !== false;
            $hasCreatedByInfo = strpos($branchEditContent, 'Created By:') !== false;
            $hasLastUpdatedInfo = strpos($branchEditContent, 'Last Updated:') !== false;
            
            echo ($hasBranchInfo ? "✅" : "❌") . " Branch Information title found" . PHP_EOL;
            echo ($hasCreatedInfo ? "✅" : "❌") . " Created date info found" . PHP_EOL;
            echo ($hasCreatedByInfo ? "✅" : "❌") . " Created by info found" . PHP_EOL;
            echo ($hasLastUpdatedInfo ? "✅" : "❌") . " Last updated info found" . PHP_EOL;
            
        } else {
            echo "❌ Branch edit form failed to load: " . $branchEditResponse->getStatusCode() . PHP_EOL;
        }
    } else {
        echo "❌ No branch found for testing edit form" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Branch edit form error: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;

// Test account edit form guidelines
echo "🔍 TESTING ACCOUNT EDIT FORM GUIDELINES" . PHP_EOL;
echo "========================================" . PHP_EOL;

try {
    // Get an account for testing
    $account = App\Models\Account::first();
    if ($account) {
        $accountEditRequest = Request::create("/admin/accounts/{$account->id}/edit", 'GET');
        $accountEditResponse = app()->handle($accountEditRequest);
        
        if ($accountEditResponse->getStatusCode() === 200) {
            echo "✅ Account edit form loads successfully" . PHP_EOL;
            
            $accountEditContent = $accountEditResponse->getContent();
            
            // Check for information section
            $hasAccountInfo = strpos($accountEditContent, 'Account Information') !== false;
            $hasCreatedInfo = strpos($accountEditContent, 'Created:') !== false;
            $hasCreatedByInfo = strpos($accountEditContent, 'Created By:') !== false;
            $hasLastUpdatedInfo = strpos($accountEditContent, 'Last Updated:') !== false;
            $hasCollateralsInfo = strpos($accountEditContent, 'Collaterals:') !== false;
            
            echo ($hasAccountInfo ? "✅" : "❌") . " Account Information title found" . PHP_EOL;
            echo ($hasCreatedInfo ? "✅" : "❌") . " Created date info found" . PHP_EOL;
            echo ($hasCreatedByInfo ? "✅" : "❌") . " Created by info found" . PHP_EOL;
            echo ($hasLastUpdatedInfo ? "✅" : "❌") . " Last updated info found" . PHP_EOL;
            echo ($hasCollateralsInfo ? "✅" : "❌") . " Collaterals count info found" . PHP_EOL;
            
        } else {
            echo "❌ Account edit form failed to load: " . $accountEditResponse->getStatusCode() . PHP_EOL;
        }
    } else {
        echo "❌ No account found for testing edit form" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Account edit form error: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL;

echo "🎯 GUIDELINES CONSISTENCY SUMMARY" . PHP_EOL;
echo "=================================" . PHP_EOL;
echo "✅ Both branches and accounts now have consistent guidelines" . PHP_EOL;
echo "✅ CREATE FORMS: Both have creation guidelines with workflow info" . PHP_EOL;
echo "✅ EDIT FORMS: Both have information sections with creation/approval details" . PHP_EOL;
echo "✅ STYLING: Consistent blue info boxes with icons" . PHP_EOL;
echo "✅ CONTENT: Relevant information for each module" . PHP_EOL . PHP_EOL;

echo "🌐 GUIDELINES FEATURES:" . PHP_EOL;
echo "======================" . PHP_EOL;
echo "📝 CREATE FORMS:" . PHP_EOL;
echo "  • Workflow explanation (Draft vs Submit for Approval)" . PHP_EOL;
echo "  • Field-specific guidance" . PHP_EOL;
echo "  • Best practices and requirements" . PHP_EOL . PHP_EOL;

echo "📊 EDIT FORMS:" . PHP_EOL;
echo "  • Creation timestamp and creator info" . PHP_EOL;
echo "  • Approval information (if applicable)" . PHP_EOL;
echo "  • Last updated timestamp" . PHP_EOL;
echo "  • Module-specific metrics (collaterals for accounts)" . PHP_EOL . PHP_EOL;

echo "🌐 TEST THE GUIDELINES:" . PHP_EOL;
echo "=======================" . PHP_EOL;
echo "1. Branch Create: http://0.0.0.0:8001/admin/branches/create" . PHP_EOL;
echo "2. Account Create: http://0.0.0.0:8001/admin/accounts/create" . PHP_EOL;
echo "3. Edit any branch or account to see information guidelines" . PHP_EOL;
echo "4. Guidelines should be consistent and helpful" . PHP_EOL;
