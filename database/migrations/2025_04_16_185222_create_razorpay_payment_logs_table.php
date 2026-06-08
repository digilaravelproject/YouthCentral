<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('razorpay_payment_logs', function (Blueprint $table) {
            // Add missing columns to the existing table
            if (!Schema::hasColumn('razorpay_payment_logs', 'razorpay_payment_id')) {
                $table->string('razorpay_payment_id')->nullable()->index();
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'razorpay_order_id')) {
                $table->string('razorpay_order_id')->nullable()->index();
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'razorpay_signature')) {
                $table->string('razorpay_signature')->nullable();
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'amount')) {
                $table->decimal('amount', 10, 2)->default(0);
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'currency')) {
                $table->string('currency')->default('INR');
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'status')) {
                $table->string('status')->default('pending');
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'entity_type')) {
                $table->string('entity_type')->nullable();
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'entity_id')) {
                $table->unsignedBigInteger('entity_id')->nullable();
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable();
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'payment_method')) {
                $table->string('payment_method')->nullable();
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'error_code')) {
                $table->string('error_code')->nullable();
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'error_description')) {
                $table->text('error_description')->nullable();
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'request_data')) {
                $table->json('request_data')->nullable();
            }
            
            if (!Schema::hasColumn('razorpay_payment_logs', 'response_data')) {
                $table->json('response_data')->nullable();
            }

            // Index creation removed - likely already exist or handled elsewhere
            // if (!Schema::hasIndex('razorpay_payment_logs', ['entity_type', 'entity_id'])) {
            //     $table->index(['entity_type', 'entity_id']);
            // }
            // 
            // if (!Schema::hasIndex('razorpay_payment_logs', ['user_id', 'status'])) {
            //     $table->index(['user_id', 'status']);
            // }
            // 
            // if (!Schema::hasIndex('razorpay_payment_logs', 'created_at')) {
            //     $table->index('created_at');
            // }
            
            // Foreign key management removed - likely already exists or handled elsewhere
            // if (Schema::hasColumn('razorpay_payment_logs', 'user_id')) {
            //     try {
            //          $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            //     } catch (\Exception $e) {
            //         Log::warning('Could not add foreign key for user_id in razorpay_payment_logs, it might already exist.', ['error' => $e->getMessage()]);
            //     }
            // }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to drop the table since we're only adding columns
        // We could drop columns here but it's safer to create a separate migration for that if needed
    }
};