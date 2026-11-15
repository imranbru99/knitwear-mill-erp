<?php

if (! function_exists('t')) {
    /**
     * @param  list<string>  $columns
     * @param  list<string>  $actions
     * @return array<string, mixed>
     */
    function t(string $model, string $panel, string $group, string $module, array $columns, bool $soft = true, bool $readonly = false, array $actions = []): array
    {
        return compact('model', 'panel', 'group', 'module', 'columns', 'soft', 'readonly', 'actions');
    }
}

/**
 * Knitwear factory domain. Each entry is a real register the floor already keeps on paper.
 *
 * @return array<string, array<string, mixed>>
 */
return [
    'branches' => t('Branch', 'admin', 'Foundation', 'foundation', [
        'code:string:unique', 'name:string', 'name_bn:string:null', 'type:string',
        'address:text:null', 'phone:string:null', 'is_active:bool:1',
    ]),
    'departments' => t('Department', 'admin', 'Foundation', 'foundation', [
        'code:string:unique', 'name:string', 'name_bn:string:null', 'head_role:string:null', 'is_active:bool:1',
    ]),
    'modules' => t('Module', 'admin', 'Foundation', 'foundation', [
        'key:string:unique', 'name:string', 'description:text:null', 'enabled:bool:1',
    ], soft: false),
    'settings' => t('Setting', 'admin', 'Foundation', 'foundation', [
        'key:string:unique', 'value:json:null',
    ], soft: false),
    'numbering_sequences' => t('NumberingSequence', 'admin', 'Foundation', 'foundation', [
        'doc_type:string', 'financial_year:string', 'prefix:string', 'last_number:int:0', 'padding:int:5',
    ], soft: false),
    'exchange_rates' => t('ExchangeRate', 'admin', 'Foundation', 'foundation', [
        'currency:string', 'rate_date:date', 'rate_to_bdt:decimal',
    ]),
    'holidays' => t('Holiday', 'admin', 'Foundation', 'foundation', [
        'holiday_date:date', 'name:string', 'name_bn:string:null',
    ]),
    'automation_rules' => t('AutomationRule', 'admin', 'Foundation', 'foundation', [
        'key:string:unique', 'name:string', 'module:string', 'enabled:bool:1', 'thresholds:json:null', 'description:text:null',
    ]),
    'approval_flows' => t('ApprovalFlow', 'admin', 'Foundation', 'foundation', [
        'document_type:string', 'name:string', 'active:bool:1',
    ]),
    'approval_flow_steps' => t('ApprovalFlowStep', 'admin', 'Foundation', 'foundation', [
        'approval_flow_id:foreign:approval_flows', 'level:int:1', 'role_name:string', 'min_amount:decimal:0',
    ]),
    'approval_requests' => t('ApprovalRequest', 'admin', 'Foundation', 'foundation', [
        'document_type:string', 'document_id:int', 'approval_flow_id:foreign:approval_flows:null',
        'status:string:d=pending', 'current_level:int:1',
    ]),
    'approval_actions' => t('ApprovalAction', 'admin', 'Foundation', 'foundation', [
        'approval_request_id:foreign:approval_requests', 'user_id:foreign:users:null', 'level:int',
        'decision:string', 'comment:text:null',
    ], soft: false),
    'document_files' => t('DocumentFile', 'admin', 'Foundation', 'foundation', [
        'document_type:string', 'document_id:int', 'path:string', 'original_name:string',
        'version:int:1', 'expires_on:date:null',
    ]),
    'audit_logs' => t('AuditLog', 'admin', 'Foundation', 'foundation', [
        'user_id:foreign:users:null', 'auditable_type:string', 'auditable_id:int', 'event:string',
        'before:json:null', 'after:json:null',
    ], soft: false, readonly: true),
    'saved_views' => t('SavedView', 'admin', 'Foundation', 'foundation', [
        'user_id:foreign:users', 'resource:string', 'name:string', 'filters:json:null',
    ]),
    'issue_types' => t('IssueType', 'admin', 'Issues', 'issues', [
        'code:string:unique', 'module:string', 'name:string', 'name_bn:string:null',
        'default_owner_role:string', 'sla_minutes:int:240', 'severity:string:d=major',
        'solution_template:text:null', 'is_active:bool:1',
    ]),
    'issues' => t('Issue', 'admin', 'Issues', 'issues', [
        'number:string:unique', 'issue_type_id:foreign:issue_types', 'severity:string', 'module:string',
        'title:string', 'description:text:null', 'source_type:string:null', 'source_id:int:null',
        'production_order_id:foreign:production_orders:null', 'machine_id:foreign:machines:null',
        'supplier_id:foreign:suppliers:null', 'department_id:foreign:departments:null',
        'owner_id:foreign:users:null', 'yarn_lot:string:null', 'defect_code:string:null',
        'due_at:datetime:null', 'status:string:d=open', 'sla_due_at:datetime:null',
        'root_cause:text:null', 'why_1:text:null', 'why_2:text:null', 'why_3:text:null', 'why_4:text:null', 'why_5:text:null',
        'corrective_action:text:null', 'preventive_action:text:null', 'verified_at:datetime:null',
        'cost_impact:decimal:0', 'time_impact_minutes:int:0', 'ai_summary:text:null',
        'parent_id:foreign:issues:null', 'chronic:bool:0', 'escalation_level:int:0',
    ], actions: ['close', 'raise']),
    'issue_actions' => t('IssueAction', 'admin', 'Issues', 'issues', [
        'issue_id:foreign:issues', 'user_id:foreign:users:null', 'action:string', 'comment:text:null',
        'from_status:string:null', 'to_status:string:null',
    ], soft: false),
    'issue_solutions' => t('IssueSolution', 'admin', 'Issues', 'issues', [
        'issue_type_id:foreign:issue_types', 'title:string', 'body:text', 'times_used:int:0',
    ]),
    'issue_escalations' => t('IssueEscalation', 'admin', 'Issues', 'issues', [
        'issue_id:foreign:issues', 'level:int', 'escalated_to_role:string', 'escalated_at:datetime', 'note:text:null',
    ], soft: false),
    'buyers' => t('Buyer', 'merchandising', 'CRM', 'crm', [
        'code:string:unique', 'name:string', 'name_bn:string:null', 'country:string:null', 'agent_name:string:null',
        'payment_terms:string:null', 'incoterm:string:null', 'currency:string:d=USD',
        'default_aql_major:decimal:2.5', 'default_aql_minor:decimal:4', 'labeling_rules:text:null',
        'packing_rules:text:null', 'credit_limit:decimal:0', 'status:string:d=active',
    ]),
    'buyer_contacts' => t('BuyerContact', 'merchandising', 'CRM', 'crm', [
        'buyer_id:foreign:buyers', 'name:string', 'email:string:null', 'phone:string:null', 'role:string:null', 'is_primary:bool:0',
    ]),
    'buyer_visits' => t('BuyerVisit', 'merchandising', 'CRM', 'crm', [
        'buyer_id:foreign:buyers', 'visited_on:date', 'notes:text:null', 'outcome:string:null',
    ]),
    'inquiries' => t('Inquiry', 'merchandising', 'CRM', 'crm', [
        'number:string:unique', 'buyer_id:foreign:buyers:null', 'source:string:d=manual', 'contact_name:string:null',
        'email:string:null', 'phone:string:null', 'style_hint:string:null', 'product_type:string:null',
        'quantity:int:0', 'target_price:decimal:null', 'currency:string:d=USD', 'timeline:string:null',
        'notes:text:null', 'status:string:d=new', 'assigned_to:foreign:users:null', 'acknowledged_at:datetime:null',
    ], actions: ['raise']),
    'quotations' => t('Quotation', 'merchandising', 'CRM', 'crm', [
        'number:string:unique', 'inquiry_id:foreign:inquiries:null', 'buyer_id:foreign:buyers:null',
        'style_id:foreign:styles:null', 'currency:string:d=USD', 'total:decimal:0', 'valid_until:date:null',
        'status:string:d=draft', 'version:int:1', 'notes:text:null',
    ], actions: ['print']),
    'suppliers' => t('Supplier', 'store', 'Purchase', 'purchase', [
        'code:string:unique', 'name:string', 'name_bn:string:null', 'type:string', 'rating:decimal:0',
        'approved:bool:0', 'country:string:null', 'phone:string:null', 'email:string:null',
        'payment_terms:string:null', 'status:string:d=active',
    ]),
    'colours' => t('Colour', 'store', 'Masters', 'styles', [
        'code:string:unique', 'name:string', 'name_bn:string:null', 'family:string:null',
    ]),
    'sizes' => t('Size', 'store', 'Masters', 'styles', [
        'code:string:unique', 'name:string', 'sort:int:0', 'system:string:d=alpha',
    ]),
    'units_of_measure' => t('UnitOfMeasure', 'store', 'Masters', 'foundation', [
        'code:string:unique', 'name:string', 'name_bn:string:null',
    ]),
    'unit_conversions' => t('UnitConversion', 'store', 'Masters', 'foundation', [
        'from_unit:string', 'to_unit:string', 'factor:decimal',
    ]),
    'yarn_counts' => t('YarnCount', 'store', 'Masters', 'styles', [
        'code:string:unique', 'name:string', 'system:string:d=Ne', 'ply:int:2', 'composition:string:null',
    ]),
    'product_categories' => t('ProductCategory', 'merchandising', 'Masters', 'styles', [
        'code:string:unique', 'name:string', 'name_bn:string:null', 'slug:string:unique', 'parent_id:int:null',
    ]),
    'styles' => t('Style', 'merchandising', 'Styles', 'styles', [
        'code:string:unique', 'name:string', 'name_bn:string:null', 'slug:string:unique',
        'buyer_id:foreign:buyers:null', 'product_category_id:foreign:product_categories:null',
        'season:string:null', 'product_type:string', 'gender:string:null', 'construction:string',
        'production_route:string', 'gauge:string:null', 'stitch:string:null', 'composition:string:null',
        'yarn_count:string:null', 'ply:int:null', 'gsm:decimal:null', 'description:text:null',
        'wash_care:text:null', 'packing_method:string:null', 'status:string:d=development', 'dpp:json:null',
    ], actions: ['raise']),
    'style_colourways' => t('StyleColourway', 'merchandising', 'Styles', 'styles', [
        'style_id:foreign:styles', 'colour_id:foreign:colours:null', 'name:string', 'shade_ref:string:null', 'status:string:d=lab_dip',
    ]),
    'style_measurements' => t('StyleMeasurement', 'merchandising', 'Styles', 'styles', [
        'style_id:foreign:styles', 'point:string', 'size_code:string', 'spec_cm:decimal', 'tolerance_plus:decimal:0.5', 'tolerance_minus:decimal:0.5',
    ]),
    'tech_pack_versions' => t('TechPackVersion', 'merchandising', 'Styles', 'styles', [
        'style_id:foreign:styles', 'version:int:1', 'file_path:string:null', 'notes:text:null', 'extracted:json:null', 'status:string:d=draft',
    ]),
    'sample_orders' => t('SampleOrder', 'merchandising', 'Sampling', 'sampling', [
        'number:string:unique', 'style_id:foreign:styles', 'buyer_id:foreign:buyers:null', 'type:string',
        'status:string:d=requested', 'requested_on:date:null', 'promised_on:date:null', 'yarn_grams:decimal:0',
        'revision:int:1', 'courier_awb:string:null', 'cost:decimal:0',
    ], actions: ['print', 'raise']),
    'sample_stages' => t('SampleStage', 'merchandising', 'Sampling', 'sampling', [
        'sample_order_id:foreign:sample_orders', 'stage:string', 'status:string:d=pending',
        'started_at:datetime:null', 'finished_at:datetime:null',
    ]),
    'sample_comments' => t('SampleComment', 'merchandising', 'Sampling', 'sampling', [
        'sample_order_id:foreign:sample_orders', 'point:string', 'comment:text', 'photo_path:string:null',
        'status:string:d=open', 'due_on:date:null',
    ]),
    'costing_sheets' => t('CostingSheet', 'merchandising', 'Costing', 'costing', [
        'number:string:unique', 'style_id:foreign:styles', 'version:int:1', 'currency:string:d=USD',
        'yarn_cost:decimal:0', 'knitting_cost:decimal:0', 'linking_cost:decimal:0', 'mending_cost:decimal:0',
        'washing_cost:decimal:0', 'finishing_cost:decimal:0', 'trims_cost:decimal:0', 'packing_cost:decimal:0',
        'testing_cost:decimal:0', 'freight:decimal:0', 'cm:decimal:0', 'overhead:decimal:0', 'finance_cost:decimal:0',
        'commission:decimal:0', 'profit_percent:decimal:10', 'total_cost:decimal:0', 'quoted_price:decimal:0',
        'target_price:decimal:null', 'status:string:d=draft',
    ], actions: ['approve', 'print']),
    'costing_lines' => t('CostingLine', 'merchandising', 'Costing', 'costing', [
        'costing_sheet_id:foreign:costing_sheets', 'component:string', 'description:string:null',
        'qty:decimal:0', 'rate:decimal:0', 'wastage_percent:decimal:0', 'amount:decimal:0',
    ]),
    'buyer_orders' => t('BuyerOrder', 'merchandising', 'Orders', 'orders', [
        'number:string:unique', 'buyer_id:foreign:buyers', 'style_id:foreign:styles', 'buyer_po_no:string',
        'currency:string:d=USD', 'quantity:int', 'ex_factory_date:date', 'ship_date:date', 'port:string:null',
        'mode:string:d=sea', 'incoterm:string:null', 'status:string:d=draft', 'production_route:string',
        'revision:int:1', 'unit_price:decimal:0',
    ], actions: ['confirm', 'print', 'raise']),
    'buyer_order_sizes' => t('BuyerOrderSize', 'merchandising', 'Orders', 'orders', [
        'buyer_order_id:foreign:buyer_orders', 'colour:string', 'size_code:string', 'quantity:int',
    ]),
    'production_orders' => t('ProductionOrder', 'merchandising', 'Orders', 'orders', [
        'number:string:unique', 'buyer_order_id:foreign:buyer_orders', 'route:string', 'quantity:int',
        'status:string:d=planned', 'unit_name:string:null', 'risk:string:d=green',
    ], actions: ['mrp', 'raise']),
    'tna_templates' => t('TnaTemplate', 'merchandising', 'Orders', 'orders', [
        'name:string', 'route:string', 'buyer_id:foreign:buyers:null', 'is_active:bool:1',
    ]),
    'tna_template_tasks' => t('TnaTemplateTask', 'merchandising', 'Orders', 'orders', [
        'tna_template_id:foreign:tna_templates', 'name:string', 'department:string', 'offset_days:int', 'sort:int:0',
    ]),
    'tna_tasks' => t('TnaTask', 'merchandising', 'Orders', 'orders', [
        'production_order_id:foreign:production_orders', 'name:string', 'department:string',
        'planned_start:date:null', 'planned_end:date', 'actual_end:date:null', 'depends_on_id:int:null',
        'status:string:d=pending', 'owner_id:foreign:users:null', 'baseline_end:date:null', 'sort:int:0',
    ]),
    'boms' => t('Bom', 'merchandising', 'BOM', 'bom', [
        'style_id:foreign:styles', 'version:int:1', 'status:string:d=draft', 'approved_at:datetime:null', 'notes:text:null',
    ]),
    'bom_lines' => t('BomLine', 'merchandising', 'BOM', 'bom', [
        'bom_id:foreign:boms', 'item_type:string', 'part:string:null', 'description:string',
        'uom:string:d=kg', 'qty_per_piece:decimal', 'wastage_percent:decimal:0', 'colour_name:string:null', 'yarn_count:string:null',
    ]),
    'material_requirements' => t('MaterialRequirement', 'merchandising', 'BOM', 'bom', [
        'production_order_id:foreign:production_orders', 'item_type:string', 'description:string', 'uom:string',
        'required_qty:decimal:0', 'available_qty:decimal:0', 'incoming_qty:decimal:0', 'reserved_qty:decimal:0',
        'shortfall_qty:decimal:0', 'status:string:d=open',
    ]),
    'requisitions' => t('Requisition', 'store', 'Purchase', 'purchase', [
        'number:string:unique', 'department:string', 'status:string:d=draft', 'needed_by:date:null', 'notes:text:null',
    ], actions: ['approve', 'print']),
    'requisition_lines' => t('RequisitionLine', 'store', 'Purchase', 'purchase', [
        'requisition_id:foreign:requisitions', 'item_type:string', 'description:string', 'qty:decimal', 'uom:string',
    ]),
    'rfqs' => t('Rfq', 'store', 'Purchase', 'purchase', [
        'number:string:unique', 'requisition_id:foreign:requisitions:null', 'status:string:d=open', 'due_on:date:null',
    ]),
    'rfq_quotes' => t('RfqQuote', 'store', 'Purchase', 'purchase', [
        'rfq_id:foreign:rfqs', 'supplier_id:foreign:suppliers', 'amount:decimal', 'currency:string:d=BDT',
        'lead_days:int:0', 'selected:bool:0', 'notes:text:null',
    ]),
    'purchase_orders' => t('PurchaseOrder', 'store', 'Purchase', 'purchase', [
        'number:string:unique', 'supplier_id:foreign:suppliers', 'requisition_id:foreign:requisitions:null',
        'currency:string:d=BDT', 'total:decimal:0', 'status:string:d=draft', 'expected_on:date:null', 'notes:text:null',
    ], actions: ['approve', 'print', 'raise']),
    'purchase_order_lines' => t('PurchaseOrderLine', 'store', 'Purchase', 'purchase', [
        'purchase_order_id:foreign:purchase_orders', 'description:string', 'qty:decimal', 'rate:decimal', 'uom:string', 'received_qty:decimal:0',
    ]),
    'grns' => t('Grn', 'store', 'Yarn store', 'yarn_store', [
        'number:string:unique', 'purchase_order_id:foreign:purchase_orders:null', 'supplier_id:foreign:suppliers',
        'challan_no:string', 'received_on:date', 'status:string:d=draft',
    ], actions: ['post', 'print', 'raise']),
    'grn_lines' => t('GrnLine', 'store', 'Yarn store', 'yarn_store', [
        'grn_id:foreign:grns', 'purchase_order_line_id:foreign:purchase_order_lines:null', 'lot_no:string',
        'item_code:string', 'qty:decimal', 'uom:string', 'gross_weight:decimal:null', 'net_weight:decimal:null',
        'moisture:decimal:null', 'shade:string:null', 'inspection_status:string:d=pending',
    ]),
    'yarn_inspections' => t('YarnInspection', 'store', 'Yarn store', 'yarn_store', [
        'grn_line_id:foreign:grn_lines', 'count_ok:bool:0', 'strength_ok:bool:0', 'hairiness_ok:bool:0',
        'neps_ok:bool:0', 'shade_ok:bool:0', 'cone_weight_ok:bool:0', 'wax_ok:bool:0', 'decision:string:d=hold', 'notes:text:null',
    ]),
    'locations' => t('Location', 'store', 'Yarn store', 'yarn_store', [
        'code:string:unique', 'name:string', 'kind:string:d=bin',
    ]),
    'stock_ledgers' => t('StockLedger', 'store', 'Yarn store', 'yarn_store', [
        'item_type:string', 'item_code:string', 'lot_no:string:null', 'location_id:foreign:locations:null',
        'qty_in:decimal:0', 'qty_out:decimal:0', 'uom:string', 'ref_type:string:null', 'ref_id:int:null',
        'happened_at:datetime', 'note:string:null',
    ], soft: false, readonly: true),
    'stock_balances' => t('StockBalance', 'store', 'Yarn store', 'yarn_store', [
        'item_type:string', 'item_code:string', 'lot_no:string', 'location_id:foreign:locations:null',
        'qty:decimal:0', 'uom:string',
    ], soft: false),
    'dyeing_challans' => t('DyeingChallan', 'store', 'Yarn store', 'yarn_store', [
        'number:string:unique', 'supplier_id:foreign:suppliers', 'shade:string', 'sent_weight:decimal',
        'received_weight:decimal:null', 'loss_percent:decimal:null', 'status:string:d=sent', 'lot_no:string',
    ], actions: ['print']),
    'gate_passes' => t('GatePass', 'store', 'Gate', 'security', [
        'number:string:unique', 'direction:string', 'returnable:bool:0', 'vehicle_no:string:null',
        'party:string', 'purpose:string', 'status:string:d=open',
    ], actions: ['print']),
    'supplier_bills' => t('SupplierBill', 'commercial', 'Accounts', 'accounts', [
        'number:string:unique', 'supplier_id:foreign:suppliers', 'purchase_order_id:foreign:purchase_orders:null',
        'grn_id:foreign:grns:null', 'amount:decimal', 'currency:string:d=BDT', 'status:string:d=unmatched', 'bill_date:date',
    ]),
    'machines' => t('Machine', 'production', 'Knitting', 'knitting', [
        'code:string:unique', 'name:string', 'brand:string:null', 'kind:string', 'gauge:string:null',
        'bed_width:string:null', 'feeders:int:null', 'speed:int:null', 'serial:string:null',
        'location_name:string:null', 'status:string:d=idle', 'operator_id:foreign:users:null',
    ]),
    'knit_programs' => t('KnitProgram', 'production', 'Knitting', 'knitting', [
        'style_id:foreign:styles', 'size_code:string', 'gauge:string', 'version:int:1', 'file_path:string:null',
        'status:string:d=draft', 'approved_by:foreign:users:null',
    ]),
    'machine_schedules' => t('MachineSchedule', 'production', 'Knitting', 'knitting', [
        'machine_id:foreign:machines', 'production_order_id:foreign:production_orders', 'starts_at:datetime',
        'ends_at:datetime', 'changeover_minutes:int:30', 'status:string:d=planned',
    ]),
    'bundles' => t('Bundle', 'production', 'Knitting', 'knitting', [
        'number:string:unique', 'barcode:string:unique', 'production_order_id:foreign:production_orders',
        'style_id:foreign:styles', 'colour:string', 'size_code:string', 'quantity:int',
        'machine_id:foreign:machines:null', 'operator_id:foreign:users:null', 'stage:string:d=knitting', 'status:string:d=open',
    ], actions: ['print']),
    'production_entries' => t('ProductionEntry', 'production', 'Knitting', 'knitting', [
        'bundle_id:foreign:bundles:null', 'machine_id:foreign:machines:null', 'operator_id:foreign:users:null',
        'shift:string:d=A', 'pieces:int:0', 'waste_grams:decimal:0', 'minutes:int:0', 'recorded_at:datetime',
    ]),
    'downtime_logs' => t('DowntimeLog', 'production', 'Knitting', 'knitting', [
        'machine_id:foreign:machines', 'reason:string', 'started_at:datetime', 'ended_at:datetime:null', 'minutes:int:0', 'note:text:null',
    ]),
    'fabric_rolls' => t('FabricRoll', 'production', 'Knitting', 'knitting', [
        'number:string:unique', 'production_order_id:foreign:production_orders', 'dia_inch:decimal:null',
        'gsm:decimal:null', 'length_m:decimal:null', 'weight_kg:decimal', 'inspection_points:int:0', 'status:string:d=in_stock',
    ]),
    'wip_scans' => t('WipScan', 'production', 'Linking', 'linking', [
        'bundle_id:foreign:bundles', 'stage:string', 'direction:string', 'scanned_at:datetime', 'operator_id:foreign:users:null',
    ], soft: false),
    'wash_recipes' => t('WashRecipe', 'production', 'Washing', 'washing', [
        'style_id:foreign:styles:null', 'name:string', 'softener_g:decimal:0', 'enzyme_g:decimal:0',
        'silicon_g:decimal:0', 'water_l:decimal:0', 'temp_c:decimal:40', 'minutes:int:20',
    ]),
    'wash_lots' => t('WashLot', 'production', 'Washing', 'washing', [
        'number:string:unique', 'wash_recipe_id:foreign:wash_recipes:null', 'production_order_id:foreign:production_orders:null',
        'status:string:d=planned', 'pieces:int:0', 'shrinkage_percent:decimal:null', 'handfeel_ok:bool:0',
    ]),
    'lab_tests' => t('LabTest', 'production', 'Quality', 'quality', [
        'number:string:unique', 'style_id:foreign:styles:null', 'wash_lot_id:foreign:wash_lots:null',
        'test_type:string', 'standard:string:null', 'result_value:string:null', 'passed:bool:0',
        'report_path:string:null', 'expires_on:date:null',
    ]),
    'cutting_orders' => t('CuttingOrder', 'production', 'Cutting', 'cutting', [
        'number:string:unique', 'production_order_id:foreign:production_orders', 'marker:string:null',
        'lay_plies:int:0', 'cut_qty:int:0', 'waste_kg:decimal:0', 'status:string:d=planned',
    ]),
    'sewing_lines' => t('SewingLine', 'production', 'Sewing', 'cutting', [
        'code:string:unique', 'name:string', 'manpower:int:0', 'target_efficiency:decimal:65',
    ]),
    'operation_breakdowns' => t('OperationBreakdown', 'production', 'Sewing', 'cutting', [
        'style_id:foreign:styles', 'operation:string', 'machine_type:string', 'smv:decimal', 'sequence:int:1',
    ]),
    'hourly_productions' => t('HourlyProduction', 'production', 'Sewing', 'cutting', [
        'sewing_line_id:foreign:sewing_lines', 'production_order_id:foreign:production_orders:null',
        'hour_at:datetime', 'output_qty:int:0', 'dhu:decimal:0',
    ]),
    'defects' => t('Defect', 'production', 'Quality', 'quality', [
        'code:string:unique', 'name:string', 'name_bn:string:null', 'category:string', 'default_severity:string:d=major',
    ]),
    'inspections' => t('Inspection', 'production', 'Quality', 'quality', [
        'number:string:unique', 'production_order_id:foreign:production_orders', 'type:string',
        'aql_major:decimal:2.5', 'aql_minor:decimal:4', 'lot_size:int', 'sample_size:int:0',
        'major_found:int:0', 'minor_found:int:0', 'critical_found:int:0', 'major_accept:int:0',
        'result:string:null', 'status:string:d=open',
    ], actions: ['judge', 'print', 'raise']),
    'inspection_defects' => t('InspectionDefect', 'production', 'Quality', 'quality', [
        'inspection_id:foreign:inspections', 'defect_id:foreign:defects:null', 'severity:string',
        'location:string:null', 'quantity:int:1', 'photo_path:string:null',
    ]),
    'packing_lists' => t('PackingList', 'commercial', 'Shipping', 'packing', [
        'number:string:unique', 'production_order_id:foreign:production_orders', 'shipment_id:foreign:shipments:null',
        'cartons:int:0', 'pieces:int:0', 'status:string:d=draft',
    ], actions: ['print']),
    'cartons' => t('Carton', 'commercial', 'Shipping', 'packing', [
        'packing_list_id:foreign:packing_lists', 'carton_no:string', 'length_cm:decimal:0', 'width_cm:decimal:0',
        'height_cm:decimal:0', 'gross_kg:decimal:0', 'net_kg:decimal:0', 'pieces:int:0',
    ]),
    'shipments' => t('Shipment', 'commercial', 'Shipping', 'packing', [
        'number:string:unique', 'buyer_order_id:foreign:buyer_orders', 'mode:string:d=sea', 'port:string:null',
        'etd:date:null', 'eta:date:null', 'container_no:string:null', 'status:string:d=booked',
        'cbm:decimal:0', 'gross_kg:decimal:0',
    ], actions: ['print', 'raise']),
    'export_lcs' => t('ExportLc', 'commercial', 'Commercial', 'commercial', [
        'number:string:unique', 'buyer_id:foreign:buyers', 'bank:string', 'amount:decimal', 'currency:string:d=USD',
        'expiry_on:date', 'shipment_deadline:date:null', 'presentation_deadline:date:null', 'status:string:d=open',
    ], actions: ['raise']),
    'btb_lcs' => t('BtbLc', 'commercial', 'Commercial', 'commercial', [
        'number:string:unique', 'export_lc_id:foreign:export_lcs', 'supplier_id:foreign:suppliers',
        'amount:decimal', 'limit_amount:decimal', 'status:string:d=open', 'maturity_on:date:null',
    ]),
    'bond_entries' => t('BondEntry', 'commercial', 'Commercial', 'commercial', [
        'export_lc_id:foreign:export_lcs', 'yarn_import_kg:decimal:0', 'garment_export_pcs:int:0',
        'yarn_consumed_kg:decimal:0', 'balance_kg:decimal:0', 'note:string:null',
    ]),
    'chart_of_accounts' => t('ChartOfAccount', 'commercial', 'Accounts', 'accounts', [
        'code:string:unique', 'name:string', 'name_bn:string:null', 'type:string', 'parent_id:int:null', 'is_active:bool:1',
    ]),
    'posting_rules' => t('PostingRule', 'commercial', 'Accounts', 'accounts', [
        'event:string:unique', 'debit_account_id:foreign:chart_of_accounts', 'credit_account_id:foreign:chart_of_accounts', 'active:bool:1',
    ]),
    'cost_centres' => t('CostCentre', 'commercial', 'Accounts', 'accounts', [
        'code:string:unique', 'name:string', 'department:string:null',
    ]),
    'vouchers' => t('Voucher', 'commercial', 'Accounts', 'accounts', [
        'number:string:unique', 'type:string', 'voucher_date:date', 'narration:text:null', 'status:string:d=draft',
        'total:decimal:0', 'source_key:string:null:unique',
    ], actions: ['post', 'print']),
    'voucher_lines' => t('VoucherLine', 'commercial', 'Accounts', 'accounts', [
        'voucher_id:foreign:vouchers', 'account_id:foreign:chart_of_accounts', 'debit:decimal:0', 'credit:decimal:0',
        'cost_centre_id:foreign:cost_centres:null', 'production_order_id:foreign:production_orders:null',
    ]),
    'employees' => t('Employee', 'hr', 'HR', 'hr', [
        'code:string:unique', 'name:string', 'name_bn:string:null', 'department_id:foreign:departments:null',
        'designation:string:null', 'grade:string:null', 'line_name:string:null', 'skill:string:null',
        'join_date:date:null', 'status:string:d=active', 'wage_type:string:d=monthly', 'basic_salary:decimal:0',
        'piece_rate:decimal:0', 'bank_account:secret:null', 'nid:secret:null', 'user_id:foreign:users:null',
    ]),
    'shifts' => t('Shift', 'hr', 'HR', 'hr', [
        'code:string:unique', 'name:string', 'starts_at:string', 'ends_at:string', 'is_night:bool:0',
    ]),
    'shift_rosters' => t('ShiftRoster', 'hr', 'HR', 'hr', [
        'employee_id:foreign:employees', 'shift_id:foreign:shifts', 'roster_date:date',
    ]),
    'leave_requests' => t('LeaveRequest', 'hr', 'HR', 'hr', [
        'employee_id:foreign:employees', 'type:string', 'starts_on:date', 'ends_on:date', 'days:decimal', 'status:string:d=pending', 'reason:text:null',
    ]),
    'payroll_runs' => t('PayrollRun', 'hr', 'HR', 'hr', [
        'number:string:unique', 'month:string', 'status:string:d=draft',
    ], actions: ['run', 'print']),
    'payslips' => t('Payslip', 'hr', 'HR', 'hr', [
        'payroll_run_id:foreign:payroll_runs', 'employee_id:foreign:employees', 'present_days:decimal:0',
        'ot_hours:decimal:0', 'piece_qty:int:0', 'gross:decimal:0', 'deductions:decimal:0', 'net:decimal:0',
    ], actions: ['print']),
    'compliance_audits' => t('ComplianceAudit', 'compliance', 'Compliance', 'compliance', [
        'standard:string', 'auditor:string:null', 'audited_on:date', 'status:string:d=open', 'next_due:date:null',
    ]),
    'audit_findings' => t('AuditFinding', 'compliance', 'Compliance', 'compliance', [
        'compliance_audit_id:foreign:compliance_audits', 'finding:text', 'severity:string:d=major',
        'due_on:date:null', 'status:string:d=open', 'capa:text:null',
    ]),
    'licences' => t('Licence', 'compliance', 'Compliance', 'compliance', [
        'name:string', 'number:string', 'expires_on:date', 'status:string:d=valid',
    ]),
    'accidents' => t('Accident', 'compliance', 'Compliance', 'compliance', [
        'happened_on:date', 'description:text', 'employee_id:foreign:employees:null', 'severity:string', 'status:string:d=open',
    ]),
    'assets' => t('Asset', 'compliance', 'Maintenance', 'maintenance', [
        'code:string:unique', 'name:string', 'kind:string', 'machine_id:foreign:machines:null', 'value:decimal:0', 'status:string:d=running',
    ]),
    'maintenance_orders' => t('MaintenanceOrder', 'compliance', 'Maintenance', 'maintenance', [
        'number:string:unique', 'asset_id:foreign:assets', 'type:string', 'status:string:d=open',
        'started_at:datetime:null', 'finished_at:datetime:null', 'cost:decimal:0', 'note:text:null',
    ]),
    'utility_readings' => t('UtilityReading', 'compliance', 'Maintenance', 'maintenance', [
        'utility:string', 'reading_on:date', 'quantity:decimal', 'unit:string',
    ]),
    'visitors' => t('Visitor', 'compliance', 'Security', 'security', [
        'name:string', 'company:string:null', 'purpose:string', 'in_at:datetime', 'out_at:datetime:null',
    ]),
    'vehicles' => t('Vehicle', 'compliance', 'Security', 'security', [
        'vehicle_no:string', 'purpose:string:null', 'in_at:datetime', 'out_at:datetime:null', 'gate_pass_id:foreign:gate_passes:null',
    ]),
    'cms_pages' => t('CmsPage', 'admin', 'Website', 'website', [
        'slug:string:unique', 'title:string', 'title_bn:string:null', 'body:text:null', 'body_bn:text:null', 'status:string:d=published',
    ]),
    'cms_posts' => t('CmsPost', 'admin', 'Website', 'website', [
        'slug:string:unique', 'title:string', 'title_bn:string:null', 'excerpt:text:null', 'body:text:null',
        'body_bn:text:null', 'published_at:datetime:null', 'status:string:d=draft',
    ]),
    'job_openings' => t('JobOpening', 'admin', 'Website', 'hr', [
        'title:string', 'title_bn:string:null', 'department:string', 'description:text', 'status:string:d=open',
    ]),
    'job_applications' => t('JobApplication', 'hr', 'HR', 'hr', [
        'job_opening_id:foreign:job_openings', 'name:string', 'email:string', 'phone:string:null', 'cv_path:string:null', 'status:string:d=new',
    ]),
    'testimonials' => t('Testimonial', 'admin', 'Website', 'website', [
        'buyer_name:string', 'quote:text', 'quote_bn:text:null', 'status:string:d=published',
    ]),
    'faqs' => t('Faq', 'admin', 'Website', 'website', [
        'question:string', 'question_bn:string:null', 'answer:text', 'answer_bn:text:null', 'sort:int:0',
    ]),
];
