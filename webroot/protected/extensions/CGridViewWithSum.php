<?php
Yii::import('zii.widgets.grid.CGridView');

class CGridViewWithSum extends CGridView
{
    public $totalSelect = false;
    protected $totalPlace = 'Header';
    protected $hasTotal = false;
    protected $totalData = '';

	/**
	 * Initializes the grid view.
	 * This method will initialize required property values and instantiate {@link columns} objects.
	 */
	public function init()
	{
		parent::init();
        $this->initTotal();
	}
    
    /**
     * Creates total line and initializes value for total.
     */

    protected function initTotal()
    {
        if(!$this->totalSelect)
        {
            return;
        }
        $this->hasTotal = true;
        $criteria = $this->dataProvider->getCriteria();
        $criteria->select = $this->totalSelect;
        $criteria->group='';
        $criteria->order='';
        $tmp = CActiveRecord::model($this->dataProvider->modelClass)->findAll($criteria);
        if(!empty($tmp))
        {
            $this->totalData=$tmp[0];
        }

    }

	/**
	 * Renders the table body.
	 */
	public function renderTableBody()
	{
		$data=$this->dataProvider->getData();
		$n=count($data);
		echo "<tbody>\n";
		if($n>0)
		{
            if($this->hasTotal)
            {
                $data[-2]=$this->totalData;
                $this->dataProvider->setData($data);
                $this->renderTableRow(-2);
                //var_dump($this->dataProvider->data);
            }
			for($row=0;$row<$n;++$row)
				$this->renderTableRow($row);
		}
		else
		{
			echo '<tr><td colspan="'.count($this->columns).'">';
			$this->renderEmptyText();
			echo "</td></tr>\n";
		}
		echo "</tbody>\n";
        //print_r($this->dataProvider->data);
    }

}
