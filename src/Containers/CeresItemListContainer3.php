<?php

namespace Ceres\Containers;

use Ceres\Config\CeresConfig;
use Plenty\Modules\Webshop\ItemSearch\SearchPresets\TagItems;
use Plenty\Modules\Webshop\ItemSearch\Services\ItemSearchService;
use Plenty\Plugin\Templates\Twig;

class CeresItemListContainer3
{
    public function call(Twig $twig, $arg):string
    {
        $tagList = [];
        
        /** @var CeresConfig $ceresConfig */
        $ceresConfig = pluginApp(CeresConfig::class);
    
        $listType = $ceresConfig->itemLists->list3Type;
    
        if($listType == 'tag_list')
        {
            /** @var ItemSearchService $itemSearchService */
            $itemSearchService = pluginApp( ItemSearchService::class );
        
            $itemSearchOptions = [
                'tagIds' => explode(',', $ceresConfig->itemLists->list3TagIds),
                'sorting' => $ceresConfig->itemLists->tagSorting
            ];
        
            $result = $itemSearchService->getResults([
                                                         'tagItems' => TagItems::getSearchFactory( $itemSearchOptions )
                                                     ]);
        
            if(count($result['tagItems']))
            {
                $tagList = $result['tagItems']['documents'];
            }
        }
        
        return $twig->render('Ceres::Containers.ItemLists.ItemList3', ["item" => $arg[0], "itemList" => $tagList]);
    }
}
