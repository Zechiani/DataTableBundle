<?php

namespace Zechiani\DataTableBundle\Model\Configuration\Language;

use Zechiani\DataTableBundle\Model\DataTableParameterBag;

class ConfigurationLanguage extends DataTableParameterBag
{   
    public function __construct(DataTableParameterBag $request)
    {
        $language = new DataTableParameterBag($request->get('language', array()));
        
        $this->set('emptyTable', $language->get('emptyTable', 'No data available in table'));
        $this->set('info', $language->get('info', 'Showing _START_ to _END_ of _TOTAL_ entries'));
        $this->set('infoEmpty', $language->get('infoEmpty', 'Showing 0 to 0 of 0 entries'));
        $this->set('infoFiltered', $language->get('infoFiltered', '(filtered from _MAX_ total entries)'));
        $this->set('infoPostFix', $language->get('infoPostFix', ''));
        $this->set('thousands', $language->get('thousands', ','));
        $this->set('lengthMenu', $language->get('lengthMenu', 'Show _MENU_ per page'));
        $this->set('loadingRecords', $language->get('loadingRecords', 'Loading...'));
        $this->set('processing', $language->get('processing', 'Processing...'));
        $this->set('search', $language->get('search', 'Search:'));
        $this->set('zeroRecords', $language->get('zeroRecords', 'No matching records found'));

        $paginate = new DataTableParameterBag($language->get('paginate', array()));
        $paginate->set('first', $paginate->get('first', 'First'));
        $paginate->set('last', $paginate->get('last', 'Last'));
        $paginate->set('next', $paginate->get('next', 'Next'));
        $paginate->set('previous', $paginate->get('previous', 'Previous'));
       
        $this->set('paginate', $paginate);
        
        $aria = new DataTableParameterBag($language->get('aria', array()));
        $aria->set('sortAscending', $aria->get('sortAscending', ': activate to sort column ascending'));
        $aria->set('sortDescending', $aria->get('sortDescending', ': activate to sort column descending'));

        $this->set('aria', $aria);
    }
}