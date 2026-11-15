# Modules

Every transactional table carries `branch_id`, `created_by`, `updated_by`, `lock_version` and an audit row. Posted documents are cancelled, not deleted. Stock is an append-only ledger. Document numbers look like `YPO-2627-00045`.

The traditional step is the screen a supervisor already knows. The automated step is what the issue engine or a scheduled command does when that step slips.

| Department | Traditional register | What the system does when it slips |
|---|---|---|
| Merchandising | Inquiry, quotation, buyer visit | Inquiry still `new` after 24 hours becomes `inquiry_silent` |
| Style room | Style, colourway, measurement, tech pack version | `techpack_revised` when the pack moves after the PO |
| Sample room | Sample order, stages, buyer comment, AWB | `sample_comment` and `sample_delay` |
| Costing | Costing sheet, profit percent, quotation PDF | `margin_drop` under the automation threshold |
| Orders | Buyer PO confirm, job, TNA from the ship date, Friday skipped | `tna_late`, `ship_risk`; daily digest at 08:00 |
| BOM | Approved BOM, grams per piece, wastage | MRP explosion, `yarn_short`, `bom_missing` |
| Purchase | Requisition, RFQ, yarn PO, approval | `supplier_late`, `price_variance` |
| Yarn store | GRN, incoming inspection, lot, issue challan | Rejected shade does not enter stock and raises `shade_mismatch` |
| Knitting | Machine, program, schedule, bundle, downtime | `machine_idle`, `dropped_stitch`, `wrong_program` |
| Linking and mending | WIP scan in and out, piece | `linking_defect`, `wip_aging` |
| Washing | Recipe, wash lot, shrinkage | `shrinkage` |
| Cutting and sewing | Cutting order, line, SMV, hourly output | `capacity_overload`, line DHU on the hourly row |
| Quality | Defect library, AQL inspection, lab test | Fail holds the job and raises `inspection_fail` |
| Packing and shipping | Packing list, carton, shipment | `carton_mismatch`, `document_discrepancy` |
| Commercial | Export LC, back-to-back, bond entry | `lc_expiry`, `btb_limit` |
| Accounts | Chart, posting rule, voucher | GRN posts Dr yarn / Cr GRNI once per `source_key` |
| HR | Employee, shift, leave, payroll, payslip | `absenteeism`, `ot_limit`. Piece rate is `qty × rate`. OT is double the hourly rate |
| Compliance | Audit, finding, licence, accident | `licence_expiry`, `finding_overdue` |
| Maintenance | Asset, PM, breakdown | `pm_overdue` |
| Gate | Visitor, vehicle, gate pass | Outward challan is the gate record |
| Issues | Type, owner, SLA, 5-Why, CAPA | Escalates to GM then MD. Three repeats on the same machine become a chronic parent. Closure needs cause, corrective action and preventive action |

Panels follow the office, not the table names: Management, Merchandising, Production, Store, Commercial, HR, Compliance.

A disabled row in `modules` hides that module's resources. Webshell is disabled until a super admin turns it on.
