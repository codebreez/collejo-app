<?php

namespace Collejo\Foundation\Menus;

use ArrayAccess;
use Gate;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Collection;

class MenuItem implements ArrayAccess, Arrayable
{
    protected $name;

    protected $label;

    protected $icon;

    protected $path;

    protected $parent;

    protected $position = 'left';

    protected $permission;

    public $children;

    public $order;

    public $type;

    /**
     * Sets the type of the menu item.
     *
     * @param $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Sets the permissions for the menu item.
     *
     * @param $permission
     *
     * @return $this
     */
    public function setPermission($permission)
    {
        $this->permission = $permission;

        return $this;
    }

    /**
     * Sets the name of the menu item.
     *
     * @param $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Sets the given menu as the current menu's parent.
     *
     * @param $name
     *
     * @return $this
     */
    public function setParent($name)
    {
        $this->parent = $name;

        return $this;
    }

    /**
     * Sets the position of the menu item in the menu template.
     *
     * @param $position
     *
     * @return $this
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Returns the name of the menu item.
     *
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets the label for the menu item.
     *
     * @param $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Gets the label for the menu item.
     *
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Sets the icon class name for the menu item.
     *
     * @param $icon
     *
     * @return $this
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Returns the icon class name for the menu item.
     *
     * @return mixed
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Sets the path for the menu item by path alias as defined in routes.
     *
     * @param $routeName
     *
     * @return $this
     */
    public function setPath($routeName)
    {
        $router = app()->make('router');

        foreach ($router->getRoutes() as $route) {
            if ($route->getName() == $routeName) {
                $this->path = $route->uri;
            }
        }

        return $this;
    }

    /**
     * Returns the path of the menu item.
     *
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Returns whether the menu item is visible to the user in session.
     *
     * @return bool
     */
    public function isVisible()
    {
        if ($this->children->count()) {
            foreach ($this->children as $child) {
                if ($child->permission && Gate::allows($child->permission)) {
                    return true;
                }
            }
        } else {
            if ($this->permission && Gate::allows($this->permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns an array of permission attached to the menu item.
     *
     * @return mixed
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * Returns the parent menu of a child menu item.
     *
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Returns the position of the menu item in the menu template.
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Returns the full qualified path of the menu item.
     *
     * @return string
     */
    public function getFullPath()
    {
        return '/'.ltrim($this->getPath(), '/');
    }

    /**
     * Sets the order of the menu item.
     *
     * @param $order
     *
     * @return $this
     */
    public function setOrder($order)
    {
        $this->order = $order;

        return $this;
    }

    public function toArray()
    {
        return [
            'name'     => $this->getName(),
            'label'    => $this->getLabel(),
            'icon'     => $this->getIcon(),
            'path'     => $this->getFullPath(),
            'parent'   => $this->getParent(),
            'position' => $this->getPosition(),
            'children' => $this->children->values(),
        ];
    }

    public function offsetExists($offset)
    {
        return property_exists($this, $offset);
    }

    public function offsetGet($offset)
    {
        return $this->$offset;
    }

    public function offsetSet($offset, $value)
    {
        $this->$offset = $value;
    }

    public function offsetUnset($offset)
    {
        $this->$offset = null;
    }

    public function __construct()
    {
        $this->children = new Collection();
    }
}