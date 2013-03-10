<?php
/**
 * RelatedSearchBehavior Class File
 *
 * Behavior making it easier to provide search functionality for relations
 * in a grid view.
 * Also uses the {@link KeenActiveDataProvider} extension to limit the number of requests
 * to the database.
 *
 * @license http://www.opensource.org/licenses/mit-license.php MIT
 *  The MIT License
 * @author Mario De Weerd
 *
 * @example
 *
 * 1. Add the RelatedSearchBehavior to the Active record class.
 * <pre>
 *    public function behaviors() {
 *        return array(
 *            'relatedsearch'=>array(
 *                'class'=>'RelatedSearchBehavior',
 *                'relations'=>array(
 *                    'serial'=>'device.device_identifier',
 *                    'location'=>'device.location.description',
 *                    /* Next line describes a field where the value to search for is $this->deviceid
 *                      (from dropdown for instance) and the value to show is 'nametoshowtouser' which
 *                      has to be available as a value of the model ('value' is optional, 'field' is used by default \*\/
 *                    'fieldwithdifferentsearchvalue'=>array('field'=>'device.displayname','searchvalue'=>'deviceid','value'=>'nametoshowtouser'),
 *                    /* Next line describes a field we do not search, but we define it here for convienience \*\/
 *                    'mylocalreference'=>'field.very.far.away.in.the.relation.tree',
 *                ),
 *		);
 *      $sort=array(
 *          'defaultOrder'=>'device_identifier DESC',
 *      );
 *		return $this->relatedSearch(
 *					$criteria,
 *					array('sort'=>$sort) // Optional default sort
 *      );
 * </pre>
 *
 * Add the new fields as safe attributes for the search scenario in rules:
 * <pre>
 * 	public function rules()
 *	{
 *
 *	    return array(
 *	        [...]
 *			array('serial,location,deviceid','safe','on'=>'search'),
 *		);
 *	}
 * </pre>
 *
 * For the CGridView column specification, you can then just put 'serial' for the column
 *  (no need to do 'name'=>..., 'filter'=>..., 'value'=>... .
 *
 * Example:
 * <pre>
 * $this->widget('zii.widgets.grid.CGridView', array(
 *  [...]
 *	'columns'=>array(
 *      [...]
 *		'serial',
 *   )
 * ));
 * </pre>
 *
 *
 * @property $owner CActiveRecord
 */
class RelatedSearchBehavior extends CActiveRecordBehavior {
    /**
     * TODO: Idea to support CDBExpressions similar to this:
        array(
                'criteria'=>array(
                        'select'=>array(
                                'DATEDIFF(t.date_expires, CURDATE()) AS datediff',
                               ),
                ),
        )
);
     Requires adding select to critéria and indication of 'with' expression...
     */

    /**
     * Extends the search criteria with related search criteria.
     *
     * @param CDbCriteria $criteria  Existing search criteria
     * @param array $relations List of properties to find through relations
     *   'key' is the local variable name, the value is the relation.
     *   <code>
     *         array(
     *              'entity_displayname'=>'entity.displayname',
     *              'owner_displayname'=>'entity.ownerUser.displayname'
     *          );
     *   </code>
     * @return KeenActiveDataProvider
     */
    public function relatedSearch($criteria,$options=array()) {

        $relations=$this->relations;

        $sort=new CSort(  ) ;
        if(isset($options['sort'])) {
            foreach($options['sort'] as $name=>$value) {
                $sort->$name=$value;
            }
        }
        $sort_attributes=array();
        $with=array();
        $sort_key="";
        if(isset($_GET[$sort->sortVar])) {
            $sort_key=$_GET[$sort->sortVar];
            if(($pos=strpos($sort_key, '.'))!==false) {
                $sort_key=substr($sort_key, 0, $pos);
            }
        }
        /*@var $dbSchema CDbSchema */
        $dbSchema=$this->getOwner()->getDbConnection()->getSchema();

        /* Convert relation properties to search and sort conditions */
        foreach($relations as $var=>$relationvar) {
            if(is_array($relationvar)) {
                $relationfield=$relationvar['field'];
                $search_value=$this->getOwner()->{$relationvar['searchvalue']};
            } else {
                $relationfield=$relationvar;
                $search_value=$this->getOwner()->{$var};
            }

            // Get relation part, table alias, and column reference in query.
            $relation=$relationfield;
            $column=$relationfield;
            // The column name itself is everything after the last dot in the relationfield.
            $pos=strrpos($relationfield, '.');
            $column=substr($relationfield, $pos+1);

            // The full relation path is everything before the last dot.
            $pos=strrpos($relation, '.');
            $relation=substr($relation, 0, $pos);

            // The join table alias is the last part of the relation.
            $shortrelation=$relation;
            if(($pos=strrpos($shortrelation, '.'))!==false) {
                $shortrelation=substr($shortrelation, $pos+1);
            }

            // The column reference in the query is the table alias + the column name.
            $column="$shortrelation.$column";
            $column=$dbSchema->quoteColumnName($column);
            /* Actual search functionality */

            // If a search is done on this relation, add compare condition and require relation in query.
            if("$search_value"!=="") {
                $with[$relation]=$relation;
                $criteria->compare($column,$search_value,true);
            }
            // If a sort is done on this relation, require the relation in the query.
            if($sort_key==="$var") {
                $with[$relation]=$relation;
            }
            // Add sort attributes (always).
            $sort_attributes["$var"] = array(
                    "asc" => $column,
                    "desc" => "$column DESC",
                    "label" => $this->getOwner()->getAttributeLabel($var),
            );

        }
        /* Always allow sorting on default attributes */
        $sort_attributes[]="*";

        if(isset($options['sort'])){
            $sort->attributes= CMap::mergeArray($sort->attributes, $sort_attributes);
        }
        else
        {
            $sort->attributes=$sort_attributes;
        }

        $criteria->mergeWith(array('with'=>array_values($with)));

        // Construct options for the data provider.
        $providerOptions=array();
        // Copy the options provides to empty array (to prevent overwriting the original array.
        $providerOptions=CMap::mergeArray($providerOptions, $options);
        // Merge our constructed options with the array.
        $providerOptions=CMap::mergeArray(
            $providerOptions,
            array(
                'criteria'=>$criteria,
                'sort'=>$sort,
            )
        );
        return new KeenActiveDataProvider($this->getOwner(), $providerOptions );
    }


    /****************************************************
     * Implementation of getter/setters for search fields
    */
    public $relations=array();

    private $_data = array();
    /**
     * Provides set search values in the 'search' scenario and database values in any other case.
     *
     * (non-PHPdoc)
     * @see CComponent::__get()
     */
    public function __get($key) {
        if($this->getOwner()->getScenario()==='search') {
            // When in the search scenario get the value for the search stored locally.
            return (array_key_exists($key,$this->_data) ? $this->_data[$key] : null);
        } else {
            // Not in search scenario - return the normal value.
            if(isset($this->relations[$key])) {
                // This field is known in our relations
                $relationvar = $this->relations[$key];
                if(is_array($relationvar)) {
                    // Complex field: has different value for search and display value.
                    if(isset($relationvar['value'])) {
                        $valueField=$relationvar['value'];
                    } else {
                        $valueField=$relationvar['field'];
                    }
                    $search_value=CHtml::value($this->getOwner(),$valueField);
                } else {
                    // Standard field: same value for searh and for display value.
                    $relationfield=$relationvar;
                    $search_value=CHtml::value($this->getOwner(),$relationvar);
                }
                return $search_value;
            }
        }
    }

    /**
     * Sets the value for the search key.
     * (non-PHPdoc)
     * @see CComponent::__set()
     */
    public function __set($key, $value) {
        if($this->getOwner()->getScenario()==='search') {
            if($this->getOwner()->isAttributeSafe($key)) {
                $this->_data[$key] = $value;
            }
        } else {
            throw new Exception("Can only set safe search attributes");
        }
    }

    /**
     * Check if a property is available.
     *
     * Relies on __isset() because any attribute here is a property.
     *
     * (non-PHPdoc)
     * @see CComponent::canGetProperty()
     */
    public function canGetProperty($name) {
        return $this->__isset($name);
    }

    /**
     * Validate properties that are save in the 'search scenario'.
     * (non-PHPdoc)
     * @see CComponent::canSetProperty()
     */
    public function canSetProperty($key) {
        if($this->getOwner()->getScenario()==='search') {
            return($this->getOwner()->isAttributeSafe($key));
        }
        return false;
    }

    /**
     * Checks if a value is available and set through this behavior.
     *
     * 1. Checks if the value was set in the search scenario (no need to test if this
     *    is the search scenario, because that is tested in the setter.
     * 2. Checks if the value is available through a defined relation (alias).
     *
     * (non-PHPdoc)
     * @see CComponent::__isset()
     */
    public function __isset($name) {
        if(array_key_exists($name,$this->_data)) {
            return true;
        } else {
            foreach($this->relations as $key=>$relationvar) {
                if($key===$name) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Unsets a value - only unsets search values.
     *
     * (non-PHPdoc)
     * @see CComponent::__unset()
     */
    public function __unset($key) {
        if(isset($this->_data[$key])) {
            unset($this->_data[$key]);
        }
    }
    /** History
     * 1.03  Quoting relatins in database.
     */
}