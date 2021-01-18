<?php

namespace Ceres\Contexts;

use IO\Helper\ContextInterface;

class PasswordResetContext extends GlobalContext implements ContextInterface
{
    /**
     * @var int The contact's ID for reset the password.
     */
    public $contactId;

    /**
     * @var string The Hash used to authenticate the password reset.
     */
    public $hash;

    /**
     * @var \Plenty\Modules\Category\Models\Category
     * Category data of the current category, linked to this class.
     */
    public $category;

    /**
     * @inheritdoc
     */
    public function init($params)
    {
        parent::init($params);

        $this->hash = $this->getParam('hash');
        $this->contactId = $this->getParam('contactId');
        $this->category = $this->getParam('category');
    }
}
