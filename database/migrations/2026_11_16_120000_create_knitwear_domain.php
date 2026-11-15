<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $foreigns = [];
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('type');
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('departments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('head_role')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('enabled')->default(true);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
        });

        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
        });

        Schema::create('numbering_sequences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('doc_type');
            $table->string('financial_year');
            $table->string('prefix');
            $table->integer('last_number')->default(0);
            $table->integer('padding')->default(5);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
        });

        Schema::create('exchange_rates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('currency');
            $table->date('rate_date');
            $table->decimal('rate_to_bdt', 14, 2);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('holidays', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->date('holiday_date');
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('automation_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('key')->unique();
            $table->string('name');
            $table->string('module');
            $table->boolean('enabled')->default(true);
            $table->json('thresholds')->nullable();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('approval_flows', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('document_type');
            $table->string('name');
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('approval_flow_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('approval_flow_id')->index();
            $table->integer('level')->default(1);
            $table->string('role_name');
            $table->decimal('min_amount', 14, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('approval_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('document_type');
            $table->integer('document_id');
            $table->unsignedBigInteger('approval_flow_id')->nullable()->index();
            $table->string('status')->default('pending');
            $table->integer('current_level')->default(1);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('approval_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('approval_request_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->integer('level');
            $table->string('decision');
            $table->text('comment')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
        });

        Schema::create('document_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('document_type');
            $table->integer('document_id');
            $table->string('path');
            $table->string('original_name');
            $table->integer('version')->default(1);
            $table->date('expires_on')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('auditable_type');
            $table->integer('auditable_id');
            $table->string('event');
            $table->json('before')->nullable();
            $table->json('after')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
        });

        Schema::create('saved_views', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->index();
            $table->string('resource');
            $table->string('name');
            $table->json('filters')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('issue_types', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('module');
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('default_owner_role');
            $table->integer('sla_minutes')->default(240);
            $table->string('severity')->default('major');
            $table->text('solution_template')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('issues', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('issue_type_id')->index();
            $table->string('severity');
            $table->string('module');
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('source_type')->nullable();
            $table->integer('source_id')->nullable();
            $table->unsignedBigInteger('production_order_id')->nullable()->index();
            $table->unsignedBigInteger('machine_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->nullable()->index();
            $table->unsignedBigInteger('department_id')->nullable()->index();
            $table->unsignedBigInteger('owner_id')->nullable()->index();
            $table->string('yarn_lot')->nullable();
            $table->string('defect_code')->nullable();
            $table->dateTime('due_at')->nullable();
            $table->string('status')->default('open');
            $table->dateTime('sla_due_at')->nullable();
            $table->text('root_cause')->nullable();
            $table->text('why_1')->nullable();
            $table->text('why_2')->nullable();
            $table->text('why_3')->nullable();
            $table->text('why_4')->nullable();
            $table->text('why_5')->nullable();
            $table->text('corrective_action')->nullable();
            $table->text('preventive_action')->nullable();
            $table->dateTime('verified_at')->nullable();
            $table->decimal('cost_impact', 14, 2)->default(0);
            $table->integer('time_impact_minutes')->default(0);
            $table->text('ai_summary')->nullable();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->boolean('chronic')->default(false);
            $table->integer('escalation_level')->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('issue_actions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('issue_id')->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->string('action');
            $table->text('comment')->nullable();
            $table->string('from_status')->nullable();
            $table->string('to_status')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
        });

        Schema::create('issue_solutions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('issue_type_id')->index();
            $table->string('title');
            $table->text('body');
            $table->integer('times_used')->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('issue_escalations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('issue_id')->index();
            $table->integer('level');
            $table->string('escalated_to_role');
            $table->dateTime('escalated_at');
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
        });

        Schema::create('buyers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('country')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('incoterm')->nullable();
            $table->string('currency')->default('USD');
            $table->decimal('default_aql_major', 14, 2)->default(2.5);
            $table->decimal('default_aql_minor', 14, 2)->default(4);
            $table->text('labeling_rules')->nullable();
            $table->text('packing_rules')->nullable();
            $table->decimal('credit_limit', 14, 2)->default(0);
            $table->string('status')->default('active');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('buyer_contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('buyer_id')->index();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('role')->nullable();
            $table->boolean('is_primary')->default(false);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('buyer_visits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('buyer_id')->index();
            $table->date('visited_on');
            $table->text('notes')->nullable();
            $table->string('outcome')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inquiries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('buyer_id')->nullable()->index();
            $table->string('source')->default('manual');
            $table->string('contact_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('style_hint')->nullable();
            $table->string('product_type')->nullable();
            $table->integer('quantity')->default(0);
            $table->decimal('target_price', 14, 2)->nullable();
            $table->string('currency')->default('USD');
            $table->string('timeline')->nullable();
            $table->text('notes')->nullable();
            $table->string('status')->default('new');
            $table->unsignedBigInteger('assigned_to')->nullable()->index();
            $table->dateTime('acknowledged_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('quotations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('inquiry_id')->nullable()->index();
            $table->unsignedBigInteger('buyer_id')->nullable()->index();
            $table->unsignedBigInteger('style_id')->nullable()->index();
            $table->string('currency')->default('USD');
            $table->decimal('total', 14, 2)->default(0);
            $table->date('valid_until')->nullable();
            $table->string('status')->default('draft');
            $table->integer('version')->default(1);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('type');
            $table->decimal('rating', 14, 2)->default(0);
            $table->boolean('approved')->default(false);
            $table->string('country')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('payment_terms')->nullable();
            $table->string('status')->default('active');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('colours', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('family')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->integer('sort')->default(0);
            $table->string('system')->default('alpha');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('units_of_measure', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('unit_conversions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('from_unit');
            $table->string('to_unit');
            $table->decimal('factor', 14, 3);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('yarn_counts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('system')->default('Ne');
            $table->integer('ply')->default(2);
            $table->string('composition')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('slug')->unique();
            $table->integer('parent_id')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('styles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('slug')->unique();
            $table->unsignedBigInteger('buyer_id')->nullable()->index();
            $table->unsignedBigInteger('product_category_id')->nullable()->index();
            $table->string('season')->nullable();
            $table->string('product_type');
            $table->string('gender')->nullable();
            $table->string('construction');
            $table->string('production_route');
            $table->string('gauge')->nullable();
            $table->string('stitch')->nullable();
            $table->string('composition')->nullable();
            $table->string('yarn_count')->nullable();
            $table->integer('ply')->nullable();
            $table->decimal('gsm', 14, 3)->nullable();
            $table->text('description')->nullable();
            $table->text('wash_care')->nullable();
            $table->string('packing_method')->nullable();
            $table->string('status')->default('development');
            $table->json('dpp')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('style_colourways', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('style_id')->index();
            $table->unsignedBigInteger('colour_id')->nullable()->index();
            $table->string('name');
            $table->string('shade_ref')->nullable();
            $table->string('status')->default('lab_dip');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('style_measurements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('style_id')->index();
            $table->string('point');
            $table->string('size_code');
            $table->decimal('spec_cm', 14, 2);
            $table->decimal('tolerance_plus', 14, 2)->default(0.5);
            $table->decimal('tolerance_minus', 14, 2)->default(0.5);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tech_pack_versions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('style_id')->index();
            $table->integer('version')->default(1);
            $table->string('file_path')->nullable();
            $table->text('notes')->nullable();
            $table->json('extracted')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sample_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('style_id')->index();
            $table->unsignedBigInteger('buyer_id')->nullable()->index();
            $table->string('type');
            $table->string('status')->default('requested');
            $table->date('requested_on')->nullable();
            $table->date('promised_on')->nullable();
            $table->decimal('yarn_grams', 14, 3)->default(0);
            $table->integer('revision')->default(1);
            $table->string('courier_awb')->nullable();
            $table->decimal('cost', 14, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sample_stages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('sample_order_id')->index();
            $table->string('stage');
            $table->string('status')->default('pending');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sample_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('sample_order_id')->index();
            $table->string('point');
            $table->text('comment');
            $table->string('photo_path')->nullable();
            $table->string('status')->default('open');
            $table->date('due_on')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('costing_sheets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('style_id')->index();
            $table->integer('version')->default(1);
            $table->string('currency')->default('USD');
            $table->decimal('yarn_cost', 14, 2)->default(0);
            $table->decimal('knitting_cost', 14, 2)->default(0);
            $table->decimal('linking_cost', 14, 2)->default(0);
            $table->decimal('mending_cost', 14, 2)->default(0);
            $table->decimal('washing_cost', 14, 2)->default(0);
            $table->decimal('finishing_cost', 14, 2)->default(0);
            $table->decimal('trims_cost', 14, 2)->default(0);
            $table->decimal('packing_cost', 14, 2)->default(0);
            $table->decimal('testing_cost', 14, 2)->default(0);
            $table->decimal('freight', 14, 2)->default(0);
            $table->decimal('cm', 14, 2)->default(0);
            $table->decimal('overhead', 14, 2)->default(0);
            $table->decimal('finance_cost', 14, 2)->default(0);
            $table->decimal('commission', 14, 2)->default(0);
            $table->decimal('profit_percent', 14, 3)->default(10);
            $table->decimal('total_cost', 14, 2)->default(0);
            $table->decimal('quoted_price', 14, 2)->default(0);
            $table->decimal('target_price', 14, 2)->nullable();
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('costing_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('costing_sheet_id')->index();
            $table->string('component');
            $table->string('description')->nullable();
            $table->decimal('qty', 14, 3)->default(0);
            $table->decimal('rate', 14, 2)->default(0);
            $table->decimal('wastage_percent', 14, 3)->default(0);
            $table->decimal('amount', 14, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('buyer_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('buyer_id')->index();
            $table->unsignedBigInteger('style_id')->index();
            $table->string('buyer_po_no');
            $table->string('currency')->default('USD');
            $table->integer('quantity');
            $table->date('ex_factory_date');
            $table->date('ship_date');
            $table->string('port')->nullable();
            $table->string('mode')->default('sea');
            $table->string('incoterm')->nullable();
            $table->string('status')->default('draft');
            $table->string('production_route');
            $table->integer('revision')->default(1);
            $table->decimal('unit_price', 14, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('buyer_order_sizes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('buyer_order_id')->index();
            $table->string('colour');
            $table->string('size_code');
            $table->integer('quantity');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('production_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('buyer_order_id')->index();
            $table->string('route');
            $table->integer('quantity');
            $table->string('status')->default('planned');
            $table->string('unit_name')->nullable();
            $table->string('risk')->default('green');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tna_templates', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('name');
            $table->string('route');
            $table->unsignedBigInteger('buyer_id')->nullable()->index();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tna_template_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('tna_template_id')->index();
            $table->string('name');
            $table->string('department');
            $table->integer('offset_days');
            $table->integer('sort')->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('tna_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('production_order_id')->index();
            $table->string('name');
            $table->string('department');
            $table->date('planned_start')->nullable();
            $table->date('planned_end');
            $table->date('actual_end')->nullable();
            $table->integer('depends_on_id')->nullable();
            $table->string('status')->default('pending');
            $table->unsignedBigInteger('owner_id')->nullable()->index();
            $table->date('baseline_end')->nullable();
            $table->integer('sort')->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('boms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('style_id')->index();
            $table->integer('version')->default(1);
            $table->string('status')->default('draft');
            $table->dateTime('approved_at')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bom_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('bom_id')->index();
            $table->string('item_type');
            $table->string('part')->nullable();
            $table->string('description');
            $table->string('uom')->default('kg');
            $table->decimal('qty_per_piece', 14, 3);
            $table->decimal('wastage_percent', 14, 3)->default(0);
            $table->string('colour_name')->nullable();
            $table->string('yarn_count')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('material_requirements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('production_order_id')->index();
            $table->string('item_type');
            $table->string('description');
            $table->string('uom');
            $table->decimal('required_qty', 14, 3)->default(0);
            $table->decimal('available_qty', 14, 3)->default(0);
            $table->decimal('incoming_qty', 14, 3)->default(0);
            $table->decimal('reserved_qty', 14, 3)->default(0);
            $table->decimal('shortfall_qty', 14, 3)->default(0);
            $table->string('status')->default('open');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->string('department');
            $table->string('status')->default('draft');
            $table->date('needed_by')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('requisition_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('requisition_id')->index();
            $table->string('item_type');
            $table->string('description');
            $table->decimal('qty', 14, 3);
            $table->string('uom');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rfqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('requisition_id')->nullable()->index();
            $table->string('status')->default('open');
            $table->date('due_on')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('rfq_quotes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('rfq_id')->index();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->decimal('amount', 14, 2);
            $table->string('currency')->default('BDT');
            $table->integer('lead_days')->default(0);
            $table->boolean('selected')->default(false);
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->unsignedBigInteger('requisition_id')->nullable()->index();
            $table->string('currency')->default('BDT');
            $table->decimal('total', 14, 2)->default(0);
            $table->string('status')->default('draft');
            $table->date('expected_on')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('purchase_order_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('purchase_order_id')->index();
            $table->string('description');
            $table->decimal('qty', 14, 3);
            $table->decimal('rate', 14, 2);
            $table->string('uom');
            $table->decimal('received_qty', 14, 3)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('grns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('purchase_order_id')->nullable()->index();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->string('challan_no');
            $table->date('received_on');
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('grn_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('grn_id')->index();
            $table->unsignedBigInteger('purchase_order_line_id')->nullable()->index();
            $table->string('lot_no');
            $table->string('item_code');
            $table->decimal('qty', 14, 3);
            $table->string('uom');
            $table->decimal('gross_weight', 14, 3)->nullable();
            $table->decimal('net_weight', 14, 3)->nullable();
            $table->decimal('moisture', 14, 3)->nullable();
            $table->string('shade')->nullable();
            $table->string('inspection_status')->default('pending');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('yarn_inspections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('grn_line_id')->index();
            $table->boolean('count_ok')->default(false);
            $table->boolean('strength_ok')->default(false);
            $table->boolean('hairiness_ok')->default(false);
            $table->boolean('neps_ok')->default(false);
            $table->boolean('shade_ok')->default(false);
            $table->boolean('cone_weight_ok')->default(false);
            $table->boolean('wax_ok')->default(false);
            $table->string('decision')->default('hold');
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('kind')->default('bin');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stock_ledgers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('item_type');
            $table->string('item_code');
            $table->string('lot_no')->nullable();
            $table->unsignedBigInteger('location_id')->nullable()->index();
            $table->decimal('qty_in', 14, 3)->default(0);
            $table->decimal('qty_out', 14, 3)->default(0);
            $table->string('uom');
            $table->string('ref_type')->nullable();
            $table->integer('ref_id')->nullable();
            $table->dateTime('happened_at');
            $table->string('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
        });

        Schema::create('stock_balances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('item_type');
            $table->string('item_code');
            $table->string('lot_no');
            $table->unsignedBigInteger('location_id')->nullable()->index();
            $table->decimal('qty', 14, 3)->default(0);
            $table->string('uom');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
        });

        Schema::create('dyeing_challans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->string('shade');
            $table->decimal('sent_weight', 14, 3);
            $table->decimal('received_weight', 14, 3)->nullable();
            $table->decimal('loss_percent', 14, 3)->nullable();
            $table->string('status')->default('sent');
            $table->string('lot_no');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('gate_passes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->string('direction');
            $table->boolean('returnable')->default(false);
            $table->string('vehicle_no')->nullable();
            $table->string('party');
            $table->string('purpose');
            $table->string('status')->default('open');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('supplier_bills', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->unsignedBigInteger('purchase_order_id')->nullable()->index();
            $table->unsignedBigInteger('grn_id')->nullable()->index();
            $table->decimal('amount', 14, 2);
            $table->string('currency')->default('BDT');
            $table->string('status')->default('unmatched');
            $table->date('bill_date');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('machines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('kind');
            $table->string('gauge')->nullable();
            $table->string('bed_width')->nullable();
            $table->integer('feeders')->nullable();
            $table->integer('speed')->nullable();
            $table->string('serial')->nullable();
            $table->string('location_name')->nullable();
            $table->string('status')->default('idle');
            $table->unsignedBigInteger('operator_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('knit_programs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('style_id')->index();
            $table->string('size_code');
            $table->string('gauge');
            $table->integer('version')->default(1);
            $table->string('file_path')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('approved_by')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('machine_schedules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('machine_id')->index();
            $table->unsignedBigInteger('production_order_id')->index();
            $table->dateTime('starts_at');
            $table->dateTime('ends_at');
            $table->integer('changeover_minutes')->default(30);
            $table->string('status')->default('planned');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->string('barcode')->unique();
            $table->unsignedBigInteger('production_order_id')->index();
            $table->unsignedBigInteger('style_id')->index();
            $table->string('colour');
            $table->string('size_code');
            $table->integer('quantity');
            $table->unsignedBigInteger('machine_id')->nullable()->index();
            $table->unsignedBigInteger('operator_id')->nullable()->index();
            $table->string('stage')->default('knitting');
            $table->string('status')->default('open');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('production_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('bundle_id')->nullable()->index();
            $table->unsignedBigInteger('machine_id')->nullable()->index();
            $table->unsignedBigInteger('operator_id')->nullable()->index();
            $table->string('shift')->default('A');
            $table->integer('pieces')->default(0);
            $table->decimal('waste_grams', 14, 3)->default(0);
            $table->integer('minutes')->default(0);
            $table->dateTime('recorded_at');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('downtime_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('machine_id')->index();
            $table->string('reason');
            $table->dateTime('started_at');
            $table->dateTime('ended_at')->nullable();
            $table->integer('minutes')->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('fabric_rolls', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('production_order_id')->index();
            $table->decimal('dia_inch', 14, 3)->nullable();
            $table->decimal('gsm', 14, 3)->nullable();
            $table->decimal('length_m', 14, 3)->nullable();
            $table->decimal('weight_kg', 14, 3);
            $table->integer('inspection_points')->default(0);
            $table->string('status')->default('in_stock');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('wip_scans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('bundle_id')->index();
            $table->string('stage');
            $table->string('direction');
            $table->dateTime('scanned_at');
            $table->unsignedBigInteger('operator_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
        });

        Schema::create('wash_recipes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('style_id')->nullable()->index();
            $table->string('name');
            $table->decimal('softener_g', 14, 2)->default(0);
            $table->decimal('enzyme_g', 14, 2)->default(0);
            $table->decimal('silicon_g', 14, 2)->default(0);
            $table->decimal('water_l', 14, 2)->default(0);
            $table->decimal('temp_c', 14, 2)->default(40);
            $table->integer('minutes')->default(20);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('wash_lots', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('wash_recipe_id')->nullable()->index();
            $table->unsignedBigInteger('production_order_id')->nullable()->index();
            $table->string('status')->default('planned');
            $table->integer('pieces')->default(0);
            $table->decimal('shrinkage_percent', 14, 3)->nullable();
            $table->boolean('handfeel_ok')->default(false);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lab_tests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('style_id')->nullable()->index();
            $table->unsignedBigInteger('wash_lot_id')->nullable()->index();
            $table->string('test_type');
            $table->string('standard')->nullable();
            $table->string('result_value')->nullable();
            $table->boolean('passed')->default(false);
            $table->string('report_path')->nullable();
            $table->date('expires_on')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cutting_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('production_order_id')->index();
            $table->string('marker')->nullable();
            $table->integer('lay_plies')->default(0);
            $table->integer('cut_qty')->default(0);
            $table->decimal('waste_kg', 14, 3)->default(0);
            $table->string('status')->default('planned');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('sewing_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->integer('manpower')->default(0);
            $table->decimal('target_efficiency', 14, 2)->default(65);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('operation_breakdowns', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('style_id')->index();
            $table->string('operation');
            $table->string('machine_type');
            $table->decimal('smv', 14, 3);
            $table->integer('sequence')->default(1);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('hourly_productions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('sewing_line_id')->index();
            $table->unsignedBigInteger('production_order_id')->nullable()->index();
            $table->dateTime('hour_at');
            $table->integer('output_qty')->default(0);
            $table->decimal('dhu', 14, 3)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('defects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('category');
            $table->string('default_severity')->default('major');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inspections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('production_order_id')->index();
            $table->string('type');
            $table->decimal('aql_major', 14, 2)->default(2.5);
            $table->decimal('aql_minor', 14, 2)->default(4);
            $table->integer('lot_size');
            $table->integer('sample_size')->default(0);
            $table->integer('major_found')->default(0);
            $table->integer('minor_found')->default(0);
            $table->integer('critical_found')->default(0);
            $table->integer('major_accept')->default(0);
            $table->string('result')->nullable();
            $table->string('status')->default('open');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('inspection_defects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('inspection_id')->index();
            $table->unsignedBigInteger('defect_id')->nullable()->index();
            $table->string('severity');
            $table->string('location')->nullable();
            $table->integer('quantity')->default(1);
            $table->string('photo_path')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('packing_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('production_order_id')->index();
            $table->unsignedBigInteger('shipment_id')->nullable()->index();
            $table->integer('cartons')->default(0);
            $table->integer('pieces')->default(0);
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cartons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('packing_list_id')->index();
            $table->string('carton_no');
            $table->decimal('length_cm', 14, 3)->default(0);
            $table->decimal('width_cm', 14, 2)->default(0);
            $table->decimal('height_cm', 14, 2)->default(0);
            $table->decimal('gross_kg', 14, 3)->default(0);
            $table->decimal('net_kg', 14, 3)->default(0);
            $table->integer('pieces')->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('buyer_order_id')->index();
            $table->string('mode')->default('sea');
            $table->string('port')->nullable();
            $table->date('etd')->nullable();
            $table->date('eta')->nullable();
            $table->string('container_no')->nullable();
            $table->string('status')->default('booked');
            $table->decimal('cbm', 14, 3)->default(0);
            $table->decimal('gross_kg', 14, 3)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('export_lcs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('buyer_id')->index();
            $table->string('bank');
            $table->decimal('amount', 14, 2);
            $table->string('currency')->default('USD');
            $table->date('expiry_on');
            $table->date('shipment_deadline')->nullable();
            $table->date('presentation_deadline')->nullable();
            $table->string('status')->default('open');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('btb_lcs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('export_lc_id')->index();
            $table->unsignedBigInteger('supplier_id')->index();
            $table->decimal('amount', 14, 2);
            $table->decimal('limit_amount', 14, 2);
            $table->string('status')->default('open');
            $table->date('maturity_on')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bond_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('export_lc_id')->index();
            $table->decimal('yarn_import_kg', 14, 3)->default(0);
            $table->integer('garment_export_pcs')->default(0);
            $table->decimal('yarn_consumed_kg', 14, 3)->default(0);
            $table->decimal('balance_kg', 14, 3)->default(0);
            $table->string('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('chart_of_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->string('type');
            $table->integer('parent_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('posting_rules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('event')->unique();
            $table->unsignedBigInteger('debit_account_id')->index();
            $table->unsignedBigInteger('credit_account_id')->index();
            $table->boolean('active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cost_centres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('department')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->string('type');
            $table->date('voucher_date');
            $table->text('narration')->nullable();
            $table->string('status')->default('draft');
            $table->decimal('total', 14, 2)->default(0);
            $table->string('source_key')->nullable()->unique();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('voucher_lines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('voucher_id')->index();
            $table->unsignedBigInteger('account_id')->index();
            $table->decimal('debit', 14, 2)->default(0);
            $table->decimal('credit', 14, 2)->default(0);
            $table->unsignedBigInteger('cost_centre_id')->nullable()->index();
            $table->unsignedBigInteger('production_order_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('name_bn')->nullable();
            $table->unsignedBigInteger('department_id')->nullable()->index();
            $table->string('designation')->nullable();
            $table->string('grade')->nullable();
            $table->string('line_name')->nullable();
            $table->string('skill')->nullable();
            $table->date('join_date')->nullable();
            $table->string('status')->default('active');
            $table->string('wage_type')->default('monthly');
            $table->decimal('basic_salary', 14, 2)->default(0);
            $table->decimal('piece_rate', 14, 2)->default(0);
            $table->string('bank_account')->nullable();
            $table->string('nid')->nullable();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('starts_at');
            $table->string('ends_at');
            $table->boolean('is_night')->default(false);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('shift_rosters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('employee_id')->index();
            $table->unsignedBigInteger('shift_id')->index();
            $table->date('roster_date');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('leave_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('employee_id')->index();
            $table->string('type');
            $table->date('starts_on');
            $table->date('ends_on');
            $table->decimal('days', 14, 2);
            $table->string('status')->default('pending');
            $table->text('reason')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payroll_runs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->string('month');
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('payslips', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('payroll_run_id')->index();
            $table->unsignedBigInteger('employee_id')->index();
            $table->decimal('present_days', 14, 2)->default(0);
            $table->decimal('ot_hours', 14, 2)->default(0);
            $table->integer('piece_qty')->default(0);
            $table->decimal('gross', 14, 2)->default(0);
            $table->decimal('deductions', 14, 2)->default(0);
            $table->decimal('net', 14, 2)->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('compliance_audits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('standard');
            $table->string('auditor')->nullable();
            $table->date('audited_on');
            $table->string('status')->default('open');
            $table->date('next_due')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('audit_findings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('compliance_audit_id')->index();
            $table->text('finding');
            $table->string('severity')->default('major');
            $table->date('due_on')->nullable();
            $table->string('status')->default('open');
            $table->text('capa')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('licences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('name');
            $table->string('number');
            $table->date('expires_on');
            $table->string('status')->default('valid');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('accidents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->date('happened_on');
            $table->text('description');
            $table->unsignedBigInteger('employee_id')->nullable()->index();
            $table->string('severity');
            $table->string('status')->default('open');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('code')->unique();
            $table->string('name');
            $table->string('kind');
            $table->unsignedBigInteger('machine_id')->nullable()->index();
            $table->decimal('value', 14, 2)->default(0);
            $table->string('status')->default('running');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('maintenance_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('number')->unique();
            $table->unsignedBigInteger('asset_id')->index();
            $table->string('type');
            $table->string('status')->default('open');
            $table->dateTime('started_at')->nullable();
            $table->dateTime('finished_at')->nullable();
            $table->decimal('cost', 14, 2)->default(0);
            $table->text('note')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('utility_readings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('utility');
            $table->date('reading_on');
            $table->decimal('quantity', 14, 2);
            $table->string('unit');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('name');
            $table->string('company')->nullable();
            $table->string('purpose');
            $table->dateTime('in_at');
            $table->dateTime('out_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('vehicle_no');
            $table->string('purpose')->nullable();
            $table->dateTime('in_at');
            $table->dateTime('out_at')->nullable();
            $table->unsignedBigInteger('gate_pass_id')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cms_pages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->text('body')->nullable();
            $table->text('body_bn')->nullable();
            $table->string('status')->default('published');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('cms_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('slug')->unique();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->text('excerpt')->nullable();
            $table->text('body')->nullable();
            $table->text('body_bn')->nullable();
            $table->dateTime('published_at')->nullable();
            $table->string('status')->default('draft');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('title');
            $table->string('title_bn')->nullable();
            $table->string('department');
            $table->text('description');
            $table->string('status')->default('open');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->unsignedBigInteger('job_opening_id')->index();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('cv_path')->nullable();
            $table->string('status')->default('new');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('buyer_name');
            $table->text('quote');
            $table->text('quote_bn')->nullable();
            $table->string('status')->default('published');
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('faqs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_id')->nullable()->index();
            $table->string('question');
            $table->string('question_bn')->nullable();
            $table->text('answer');
            $table->text('answer_bn')->nullable();
            $table->integer('sort')->default(0);
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable()->index();
            $table->unsignedInteger('lock_version')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });

        // SQLite rebuilds the whole table for every added foreign key.
        if (Schema::getConnection()->getDriverName() !== 'sqlite') {
            Schema::table('branches', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('departments', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('numbering_sequences', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('exchange_rates', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('holidays', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('automation_rules', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('approval_flows', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('approval_flow_steps', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('approval_flow_steps', function (Blueprint $table) {
                $table->foreign('approval_flow_id')->references('id')->on('approval_flows')->nullOnDelete();
            });
            Schema::table('approval_requests', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('approval_requests', function (Blueprint $table) {
                $table->foreign('approval_flow_id')->references('id')->on('approval_flows')->nullOnDelete();
            });
            Schema::table('approval_actions', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('approval_actions', function (Blueprint $table) {
                $table->foreign('approval_request_id')->references('id')->on('approval_requests')->nullOnDelete();
            });
            Schema::table('approval_actions', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('document_files', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('audit_logs', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('saved_views', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('saved_views', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('issue_types', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('issues', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('issues', function (Blueprint $table) {
                $table->foreign('issue_type_id')->references('id')->on('issue_types')->nullOnDelete();
            });
            Schema::table('issues', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('issues', function (Blueprint $table) {
                $table->foreign('machine_id')->references('id')->on('machines')->nullOnDelete();
            });
            Schema::table('issues', function (Blueprint $table) {
                $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete();
            });
            Schema::table('issues', function (Blueprint $table) {
                $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            });
            Schema::table('issues', function (Blueprint $table) {
                $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('issues', function (Blueprint $table) {
                $table->foreign('parent_id')->references('id')->on('issues')->nullOnDelete();
            });
            Schema::table('issue_actions', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('issue_actions', function (Blueprint $table) {
                $table->foreign('issue_id')->references('id')->on('issues')->nullOnDelete();
            });
            Schema::table('issue_actions', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('issue_solutions', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('issue_solutions', function (Blueprint $table) {
                $table->foreign('issue_type_id')->references('id')->on('issue_types')->nullOnDelete();
            });
            Schema::table('issue_escalations', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('issue_escalations', function (Blueprint $table) {
                $table->foreign('issue_id')->references('id')->on('issues')->nullOnDelete();
            });
            Schema::table('buyers', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('buyer_contacts', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('buyer_contacts', function (Blueprint $table) {
                $table->foreign('buyer_id')->references('id')->on('buyers')->nullOnDelete();
            });
            Schema::table('buyer_visits', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('buyer_visits', function (Blueprint $table) {
                $table->foreign('buyer_id')->references('id')->on('buyers')->nullOnDelete();
            });
            Schema::table('inquiries', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('inquiries', function (Blueprint $table) {
                $table->foreign('buyer_id')->references('id')->on('buyers')->nullOnDelete();
            });
            Schema::table('inquiries', function (Blueprint $table) {
                $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('quotations', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('quotations', function (Blueprint $table) {
                $table->foreign('inquiry_id')->references('id')->on('inquiries')->nullOnDelete();
            });
            Schema::table('quotations', function (Blueprint $table) {
                $table->foreign('buyer_id')->references('id')->on('buyers')->nullOnDelete();
            });
            Schema::table('quotations', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('suppliers', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('colours', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('sizes', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('units_of_measure', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('unit_conversions', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('yarn_counts', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('product_categories', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('styles', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('styles', function (Blueprint $table) {
                $table->foreign('buyer_id')->references('id')->on('buyers')->nullOnDelete();
            });
            Schema::table('styles', function (Blueprint $table) {
                $table->foreign('product_category_id')->references('id')->on('product_categories')->nullOnDelete();
            });
            Schema::table('style_colourways', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('style_colourways', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('style_colourways', function (Blueprint $table) {
                $table->foreign('colour_id')->references('id')->on('colours')->nullOnDelete();
            });
            Schema::table('style_measurements', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('style_measurements', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('tech_pack_versions', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('tech_pack_versions', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('sample_orders', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('sample_orders', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('sample_orders', function (Blueprint $table) {
                $table->foreign('buyer_id')->references('id')->on('buyers')->nullOnDelete();
            });
            Schema::table('sample_stages', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('sample_stages', function (Blueprint $table) {
                $table->foreign('sample_order_id')->references('id')->on('sample_orders')->nullOnDelete();
            });
            Schema::table('sample_comments', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('sample_comments', function (Blueprint $table) {
                $table->foreign('sample_order_id')->references('id')->on('sample_orders')->nullOnDelete();
            });
            Schema::table('costing_sheets', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('costing_sheets', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('costing_lines', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('costing_lines', function (Blueprint $table) {
                $table->foreign('costing_sheet_id')->references('id')->on('costing_sheets')->nullOnDelete();
            });
            Schema::table('buyer_orders', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('buyer_orders', function (Blueprint $table) {
                $table->foreign('buyer_id')->references('id')->on('buyers')->nullOnDelete();
            });
            Schema::table('buyer_orders', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('buyer_order_sizes', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('buyer_order_sizes', function (Blueprint $table) {
                $table->foreign('buyer_order_id')->references('id')->on('buyer_orders')->nullOnDelete();
            });
            Schema::table('production_orders', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('production_orders', function (Blueprint $table) {
                $table->foreign('buyer_order_id')->references('id')->on('buyer_orders')->nullOnDelete();
            });
            Schema::table('tna_templates', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('tna_templates', function (Blueprint $table) {
                $table->foreign('buyer_id')->references('id')->on('buyers')->nullOnDelete();
            });
            Schema::table('tna_template_tasks', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('tna_template_tasks', function (Blueprint $table) {
                $table->foreign('tna_template_id')->references('id')->on('tna_templates')->nullOnDelete();
            });
            Schema::table('tna_tasks', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('tna_tasks', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('tna_tasks', function (Blueprint $table) {
                $table->foreign('owner_id')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('boms', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('boms', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('bom_lines', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('bom_lines', function (Blueprint $table) {
                $table->foreign('bom_id')->references('id')->on('boms')->nullOnDelete();
            });
            Schema::table('material_requirements', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('material_requirements', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('requisitions', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('requisition_lines', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('requisition_lines', function (Blueprint $table) {
                $table->foreign('requisition_id')->references('id')->on('requisitions')->nullOnDelete();
            });
            Schema::table('rfqs', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('rfqs', function (Blueprint $table) {
                $table->foreign('requisition_id')->references('id')->on('requisitions')->nullOnDelete();
            });
            Schema::table('rfq_quotes', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('rfq_quotes', function (Blueprint $table) {
                $table->foreign('rfq_id')->references('id')->on('rfqs')->nullOnDelete();
            });
            Schema::table('rfq_quotes', function (Blueprint $table) {
                $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete();
            });
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete();
            });
            Schema::table('purchase_orders', function (Blueprint $table) {
                $table->foreign('requisition_id')->references('id')->on('requisitions')->nullOnDelete();
            });
            Schema::table('purchase_order_lines', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('purchase_order_lines', function (Blueprint $table) {
                $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->nullOnDelete();
            });
            Schema::table('grns', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('grns', function (Blueprint $table) {
                $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->nullOnDelete();
            });
            Schema::table('grns', function (Blueprint $table) {
                $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete();
            });
            Schema::table('grn_lines', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('grn_lines', function (Blueprint $table) {
                $table->foreign('grn_id')->references('id')->on('grns')->nullOnDelete();
            });
            Schema::table('grn_lines', function (Blueprint $table) {
                $table->foreign('purchase_order_line_id')->references('id')->on('purchase_order_lines')->nullOnDelete();
            });
            Schema::table('yarn_inspections', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('yarn_inspections', function (Blueprint $table) {
                $table->foreign('grn_line_id')->references('id')->on('grn_lines')->nullOnDelete();
            });
            Schema::table('locations', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('stock_ledgers', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('stock_ledgers', function (Blueprint $table) {
                $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete();
            });
            Schema::table('stock_balances', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('stock_balances', function (Blueprint $table) {
                $table->foreign('location_id')->references('id')->on('locations')->nullOnDelete();
            });
            Schema::table('dyeing_challans', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('dyeing_challans', function (Blueprint $table) {
                $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete();
            });
            Schema::table('gate_passes', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('supplier_bills', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('supplier_bills', function (Blueprint $table) {
                $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete();
            });
            Schema::table('supplier_bills', function (Blueprint $table) {
                $table->foreign('purchase_order_id')->references('id')->on('purchase_orders')->nullOnDelete();
            });
            Schema::table('supplier_bills', function (Blueprint $table) {
                $table->foreign('grn_id')->references('id')->on('grns')->nullOnDelete();
            });
            Schema::table('machines', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('machines', function (Blueprint $table) {
                $table->foreign('operator_id')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('knit_programs', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('knit_programs', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('knit_programs', function (Blueprint $table) {
                $table->foreign('approved_by')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('machine_schedules', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('machine_schedules', function (Blueprint $table) {
                $table->foreign('machine_id')->references('id')->on('machines')->nullOnDelete();
            });
            Schema::table('machine_schedules', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('bundles', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('bundles', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('bundles', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('bundles', function (Blueprint $table) {
                $table->foreign('machine_id')->references('id')->on('machines')->nullOnDelete();
            });
            Schema::table('bundles', function (Blueprint $table) {
                $table->foreign('operator_id')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('production_entries', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('production_entries', function (Blueprint $table) {
                $table->foreign('bundle_id')->references('id')->on('bundles')->nullOnDelete();
            });
            Schema::table('production_entries', function (Blueprint $table) {
                $table->foreign('machine_id')->references('id')->on('machines')->nullOnDelete();
            });
            Schema::table('production_entries', function (Blueprint $table) {
                $table->foreign('operator_id')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('downtime_logs', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('downtime_logs', function (Blueprint $table) {
                $table->foreign('machine_id')->references('id')->on('machines')->nullOnDelete();
            });
            Schema::table('fabric_rolls', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('fabric_rolls', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('wip_scans', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('wip_scans', function (Blueprint $table) {
                $table->foreign('bundle_id')->references('id')->on('bundles')->nullOnDelete();
            });
            Schema::table('wip_scans', function (Blueprint $table) {
                $table->foreign('operator_id')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('wash_recipes', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('wash_recipes', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('wash_lots', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('wash_lots', function (Blueprint $table) {
                $table->foreign('wash_recipe_id')->references('id')->on('wash_recipes')->nullOnDelete();
            });
            Schema::table('wash_lots', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('lab_tests', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('lab_tests', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('lab_tests', function (Blueprint $table) {
                $table->foreign('wash_lot_id')->references('id')->on('wash_lots')->nullOnDelete();
            });
            Schema::table('cutting_orders', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('cutting_orders', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('sewing_lines', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('operation_breakdowns', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('operation_breakdowns', function (Blueprint $table) {
                $table->foreign('style_id')->references('id')->on('styles')->nullOnDelete();
            });
            Schema::table('hourly_productions', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('hourly_productions', function (Blueprint $table) {
                $table->foreign('sewing_line_id')->references('id')->on('sewing_lines')->nullOnDelete();
            });
            Schema::table('hourly_productions', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('defects', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('inspections', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('inspections', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('inspection_defects', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('inspection_defects', function (Blueprint $table) {
                $table->foreign('inspection_id')->references('id')->on('inspections')->nullOnDelete();
            });
            Schema::table('inspection_defects', function (Blueprint $table) {
                $table->foreign('defect_id')->references('id')->on('defects')->nullOnDelete();
            });
            Schema::table('packing_lists', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('packing_lists', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('packing_lists', function (Blueprint $table) {
                $table->foreign('shipment_id')->references('id')->on('shipments')->nullOnDelete();
            });
            Schema::table('cartons', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('cartons', function (Blueprint $table) {
                $table->foreign('packing_list_id')->references('id')->on('packing_lists')->nullOnDelete();
            });
            Schema::table('shipments', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('shipments', function (Blueprint $table) {
                $table->foreign('buyer_order_id')->references('id')->on('buyer_orders')->nullOnDelete();
            });
            Schema::table('export_lcs', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('export_lcs', function (Blueprint $table) {
                $table->foreign('buyer_id')->references('id')->on('buyers')->nullOnDelete();
            });
            Schema::table('btb_lcs', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('btb_lcs', function (Blueprint $table) {
                $table->foreign('export_lc_id')->references('id')->on('export_lcs')->nullOnDelete();
            });
            Schema::table('btb_lcs', function (Blueprint $table) {
                $table->foreign('supplier_id')->references('id')->on('suppliers')->nullOnDelete();
            });
            Schema::table('bond_entries', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('bond_entries', function (Blueprint $table) {
                $table->foreign('export_lc_id')->references('id')->on('export_lcs')->nullOnDelete();
            });
            Schema::table('chart_of_accounts', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('posting_rules', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('posting_rules', function (Blueprint $table) {
                $table->foreign('debit_account_id')->references('id')->on('chart_of_accounts')->nullOnDelete();
            });
            Schema::table('posting_rules', function (Blueprint $table) {
                $table->foreign('credit_account_id')->references('id')->on('chart_of_accounts')->nullOnDelete();
            });
            Schema::table('cost_centres', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('vouchers', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('voucher_lines', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('voucher_lines', function (Blueprint $table) {
                $table->foreign('voucher_id')->references('id')->on('vouchers')->nullOnDelete();
            });
            Schema::table('voucher_lines', function (Blueprint $table) {
                $table->foreign('account_id')->references('id')->on('chart_of_accounts')->nullOnDelete();
            });
            Schema::table('voucher_lines', function (Blueprint $table) {
                $table->foreign('cost_centre_id')->references('id')->on('cost_centres')->nullOnDelete();
            });
            Schema::table('voucher_lines', function (Blueprint $table) {
                $table->foreign('production_order_id')->references('id')->on('production_orders')->nullOnDelete();
            });
            Schema::table('employees', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('employees', function (Blueprint $table) {
                $table->foreign('department_id')->references('id')->on('departments')->nullOnDelete();
            });
            Schema::table('employees', function (Blueprint $table) {
                $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
            });
            Schema::table('shifts', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('shift_rosters', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('shift_rosters', function (Blueprint $table) {
                $table->foreign('employee_id')->references('id')->on('employees')->nullOnDelete();
            });
            Schema::table('shift_rosters', function (Blueprint $table) {
                $table->foreign('shift_id')->references('id')->on('shifts')->nullOnDelete();
            });
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('leave_requests', function (Blueprint $table) {
                $table->foreign('employee_id')->references('id')->on('employees')->nullOnDelete();
            });
            Schema::table('payroll_runs', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('payslips', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('payslips', function (Blueprint $table) {
                $table->foreign('payroll_run_id')->references('id')->on('payroll_runs')->nullOnDelete();
            });
            Schema::table('payslips', function (Blueprint $table) {
                $table->foreign('employee_id')->references('id')->on('employees')->nullOnDelete();
            });
            Schema::table('compliance_audits', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('audit_findings', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('audit_findings', function (Blueprint $table) {
                $table->foreign('compliance_audit_id')->references('id')->on('compliance_audits')->nullOnDelete();
            });
            Schema::table('licences', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('accidents', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('accidents', function (Blueprint $table) {
                $table->foreign('employee_id')->references('id')->on('employees')->nullOnDelete();
            });
            Schema::table('assets', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('assets', function (Blueprint $table) {
                $table->foreign('machine_id')->references('id')->on('machines')->nullOnDelete();
            });
            Schema::table('maintenance_orders', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('maintenance_orders', function (Blueprint $table) {
                $table->foreign('asset_id')->references('id')->on('assets')->nullOnDelete();
            });
            Schema::table('utility_readings', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('visitors', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('vehicles', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('vehicles', function (Blueprint $table) {
                $table->foreign('gate_pass_id')->references('id')->on('gate_passes')->nullOnDelete();
            });
            Schema::table('cms_pages', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('cms_posts', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('job_openings', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('job_applications', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('job_applications', function (Blueprint $table) {
                $table->foreign('job_opening_id')->references('id')->on('job_openings')->nullOnDelete();
            });
            Schema::table('testimonials', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
            Schema::table('faqs', function (Blueprint $table) {
                $table->foreign('branch_id')->references('id')->on('branches')->nullOnDelete();
            });
        }

    }

    public function down(): void
    {
        Schema::dropIfExists('faqs');
        Schema::dropIfExists('testimonials');
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_openings');
        Schema::dropIfExists('cms_posts');
        Schema::dropIfExists('cms_pages');
        Schema::dropIfExists('vehicles');
        Schema::dropIfExists('visitors');
        Schema::dropIfExists('utility_readings');
        Schema::dropIfExists('maintenance_orders');
        Schema::dropIfExists('assets');
        Schema::dropIfExists('accidents');
        Schema::dropIfExists('licences');
        Schema::dropIfExists('audit_findings');
        Schema::dropIfExists('compliance_audits');
        Schema::dropIfExists('payslips');
        Schema::dropIfExists('payroll_runs');
        Schema::dropIfExists('leave_requests');
        Schema::dropIfExists('shift_rosters');
        Schema::dropIfExists('shifts');
        Schema::dropIfExists('employees');
        Schema::dropIfExists('voucher_lines');
        Schema::dropIfExists('vouchers');
        Schema::dropIfExists('cost_centres');
        Schema::dropIfExists('posting_rules');
        Schema::dropIfExists('chart_of_accounts');
        Schema::dropIfExists('bond_entries');
        Schema::dropIfExists('btb_lcs');
        Schema::dropIfExists('export_lcs');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('cartons');
        Schema::dropIfExists('packing_lists');
        Schema::dropIfExists('inspection_defects');
        Schema::dropIfExists('inspections');
        Schema::dropIfExists('defects');
        Schema::dropIfExists('hourly_productions');
        Schema::dropIfExists('operation_breakdowns');
        Schema::dropIfExists('sewing_lines');
        Schema::dropIfExists('cutting_orders');
        Schema::dropIfExists('lab_tests');
        Schema::dropIfExists('wash_lots');
        Schema::dropIfExists('wash_recipes');
        Schema::dropIfExists('wip_scans');
        Schema::dropIfExists('fabric_rolls');
        Schema::dropIfExists('downtime_logs');
        Schema::dropIfExists('production_entries');
        Schema::dropIfExists('bundles');
        Schema::dropIfExists('machine_schedules');
        Schema::dropIfExists('knit_programs');
        Schema::dropIfExists('machines');
        Schema::dropIfExists('supplier_bills');
        Schema::dropIfExists('gate_passes');
        Schema::dropIfExists('dyeing_challans');
        Schema::dropIfExists('stock_balances');
        Schema::dropIfExists('stock_ledgers');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('yarn_inspections');
        Schema::dropIfExists('grn_lines');
        Schema::dropIfExists('grns');
        Schema::dropIfExists('purchase_order_lines');
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('rfq_quotes');
        Schema::dropIfExists('rfqs');
        Schema::dropIfExists('requisition_lines');
        Schema::dropIfExists('requisitions');
        Schema::dropIfExists('material_requirements');
        Schema::dropIfExists('bom_lines');
        Schema::dropIfExists('boms');
        Schema::dropIfExists('tna_tasks');
        Schema::dropIfExists('tna_template_tasks');
        Schema::dropIfExists('tna_templates');
        Schema::dropIfExists('production_orders');
        Schema::dropIfExists('buyer_order_sizes');
        Schema::dropIfExists('buyer_orders');
        Schema::dropIfExists('costing_lines');
        Schema::dropIfExists('costing_sheets');
        Schema::dropIfExists('sample_comments');
        Schema::dropIfExists('sample_stages');
        Schema::dropIfExists('sample_orders');
        Schema::dropIfExists('tech_pack_versions');
        Schema::dropIfExists('style_measurements');
        Schema::dropIfExists('style_colourways');
        Schema::dropIfExists('styles');
        Schema::dropIfExists('product_categories');
        Schema::dropIfExists('yarn_counts');
        Schema::dropIfExists('unit_conversions');
        Schema::dropIfExists('units_of_measure');
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('colours');
        Schema::dropIfExists('suppliers');
        Schema::dropIfExists('quotations');
        Schema::dropIfExists('inquiries');
        Schema::dropIfExists('buyer_visits');
        Schema::dropIfExists('buyer_contacts');
        Schema::dropIfExists('buyers');
        Schema::dropIfExists('issue_escalations');
        Schema::dropIfExists('issue_solutions');
        Schema::dropIfExists('issue_actions');
        Schema::dropIfExists('issues');
        Schema::dropIfExists('issue_types');
        Schema::dropIfExists('saved_views');
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('document_files');
        Schema::dropIfExists('approval_actions');
        Schema::dropIfExists('approval_requests');
        Schema::dropIfExists('approval_flow_steps');
        Schema::dropIfExists('approval_flows');
        Schema::dropIfExists('automation_rules');
        Schema::dropIfExists('holidays');
        Schema::dropIfExists('exchange_rates');
        Schema::dropIfExists('numbering_sequences');
        Schema::dropIfExists('settings');
        Schema::dropIfExists('modules');
        Schema::dropIfExists('departments');
        Schema::dropIfExists('branches');
    }
};
