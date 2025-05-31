<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Address;
use App\Models\Branch;
use App\Models\Account;
use App\Models\Collateral;
use App\Models\CollateralImage;
use App\Models\Bid;
use App\Models\AuditLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSchemaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /** @test */
    public function it_creates_users_with_uuid_primary_keys()
    {
        $user = User::where('username', 'admin')->first();
        
        $this->assertNotNull($user);
        $this->assertTrue(is_string($user->id));
        $this->assertEquals(36, strlen($user->id)); // UUID length
        $this->assertTrue($user->is_admin);
        $this->assertEquals('checker', $user->role);
    }

    /** @test */
    public function it_creates_islamic_pawnbroking_structure()
    {
        // Test hierarchical structure: Branches > Accounts > Collaterals
        $branch = Branch::where('status', Branch::STATUS_ACTIVE)->first();
        $this->assertNotNull($branch);
        
        $account = $branch->accounts()->first();
        $this->assertNotNull($account);
        
        $collateral = $account->collaterals()->first();
        $this->assertNotNull($collateral);
        
        // Test Islamic compliance fields
        $this->assertNotNull($collateral->weight_grams);
        $this->assertNotNull($collateral->purity);
        $this->assertNotNull($collateral->estimated_value_rm);
    }

    /** @test */
    public function it_implements_maker_checker_workflow()
    {
        $maker = User::where('role', User::ROLE_MAKER)->first();
        $checker = User::where('role', User::ROLE_CHECKER)->first();
        
        $this->assertNotNull($maker);
        $this->assertNotNull($checker);
        
        // Test approved entities have both maker and checker
        $approvedBranch = Branch::where('status', Branch::STATUS_ACTIVE)->first();
        $this->assertNotNull($approvedBranch->created_by_user_id);
        $this->assertNotNull($approvedBranch->approved_by_user_id);
    }

    /** @test */
    public function it_supports_auction_functionality()
    {
        $auctioningCollateral = Collateral::where('status', Collateral::STATUS_AUCTIONING)->first();
        
        if ($auctioningCollateral) {
            $this->assertNotNull($auctioningCollateral->auction_start_datetime);
            $this->assertNotNull($auctioningCollateral->auction_end_datetime);
            $this->assertGreaterThan(0, $auctioningCollateral->starting_bid_rm);
            
            // Test bids
            $bids = $auctioningCollateral->bids;
            $this->assertGreaterThan(0, $bids->count());
        }
    }

    /** @test */
    public function it_maintains_audit_trails()
    {
        $auditLogs = AuditLog::all();
        $this->assertGreaterThan(0, $auditLogs->count());
        
        $log = $auditLogs->first();
        $this->assertNotNull($log->action_type);
        $this->assertNotNull($log->module_affected);
        $this->assertNotNull($log->timestamp);
    }

    /** @test */
    public function it_handles_soft_deletes()
    {
        $user = User::first();
        $originalCount = User::count();
        
        $user->delete();
        
        // Should be soft deleted
        $this->assertEquals($originalCount - 1, User::count());
        $this->assertEquals($originalCount, User::withTrashed()->count());
        
        // Should be restorable
        $user->restore();
        $this->assertEquals($originalCount, User::count());
    }

    /** @test */
    public function it_supports_malaysian_customer_data()
    {
        $account = Account::where('status', Account::STATUS_ACTIVE)->first();
        
        $this->assertNotNull($account->customer_name);
        $this->assertNotNull($account->nric_passport);
        $this->assertNotNull($account->loan_amount_granted);
        $this->assertContains($account->repayment_status, [
            Account::REPAYMENT_OUTSTANDING,
            Account::REPAYMENT_PAID_OFF,
            Account::REPAYMENT_DEFAULTED
        ]);
    }

    /** @test */
    public function it_manages_collateral_images()
    {
        $collateral = Collateral::first();
        $images = $collateral->images;
        
        $this->assertGreaterThan(0, $images->count());
        
        $thumbnail = $images->where('is_thumbnail', true)->first();
        $this->assertNotNull($thumbnail);
        $this->assertNotNull($thumbnail->image_url);
    }

    /** @test */
    public function it_tracks_bidding_activity()
    {
        $bid = Bid::first();
        
        if ($bid) {
            $this->assertNotNull($bid->collateral_id);
            $this->assertNotNull($bid->user_id);
            $this->assertGreaterThan(0, $bid->bid_amount_rm);
            $this->assertNotNull($bid->bid_time);
            $this->assertContains($bid->status, [
                Bid::STATUS_ACTIVE,
                Bid::STATUS_OUTBID,
                Bid::STATUS_WINNING,
                Bid::STATUS_CANCELLED
            ]);
        }
    }

    /** @test */
    public function it_maintains_referential_integrity()
    {
        // Test that all foreign keys are properly set
        $account = Account::with(['branch', 'createdBy', 'approvedBy'])->first();
        
        $this->assertNotNull($account->branch);
        $this->assertNotNull($account->createdBy);
        
        if ($account->approved_by_user_id) {
            $this->assertNotNull($account->approvedBy);
        }
    }
}
