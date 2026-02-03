<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Modules\Payment\Models\InvoiceModel;

class ExpireInvoices extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Invoices';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'invoices:expire';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Updates unpaid invoices with past due dates to expired status.';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'invoices:expire';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        CLI::write('Processing expired invoices...', 'yellow');

        try {
            $invoiceModel = new InvoiceModel();
            $updatedCount = $invoiceModel->processExpiredInvoices();

            if ($updatedCount > 0) {
                CLI::write("Success: {$updatedCount} invoice(s) have been marked as expired.", 'green');
            } else {
                CLI::write('No unpaid invoices with past due dates found.', 'white');
            }
        } catch (\Exception $e) {
            CLI::error('Error processing expired invoices: ' . $e->getMessage());
        }
    }
}
