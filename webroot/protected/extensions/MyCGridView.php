<?php
/**
 * MyCGridView class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright Copyright &copy; 2008-2010 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

Yii::import('zii.widgets.grid.CGridView');

class MyCGridView extends CGridView
{
    public $total = array();
    protected $totalLabel = 'Total';
    protected $totalPlace = 'Header';
    protected $hasTotal = false;
    public $totalValue = array();
    

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
        if($this->total===array())
        {
            return;
        }
        $this->hasTotal = true;
        if(isset($this->total['label']))
        {
            $this->totalLabel = $this->total['label'];
        }
        if($this->dataProvider->getPagination())
        {
            $this->totalLabel .= ' on page';
        }
        $this->totalLabel .= ':';
        if(isset($this->total['place']))
        {
            $this->totalPlace = $this->total['place'];
        }
        foreach($this->columns as $i=>$column)
        {
            switch ($this->total['columns'][$i])
            {
                case 'total':
                    $this->totalValue[$i]['value'] = $this->totalLabel;
                    break;
                case 'sum':
                    $this->totalValue[$i]['value'] = $this->GetSumColumn($column);
                    break;
                case '-':
                    $this->totalValue[$i]['value'] = null ; //$this->nullDisplay;
                    break;
                default :
                    $this->totalValue[$i]['value'] = $this->total['columns'][$i] ; //$this->nullDisplay;
            }
            if(isset($column->type) and !is_null($column->type))
            {
                if ('money4' === $column->type)
                {
                    $this->totalValue[$i]['type'] = 'money';
                }
                else
                {
                    $this->totalValue[$i]['type'] = $column->type;
                }
            }
            if(!empty($column->htmlOptions))
            {
                $this->totalValue[$i]['htmlOptions'] = $column->htmlOptions;
            }
            if('Footer' == $this->totalPlace)
            {
                $column->footer = $this->totalValue[$i]['value'];
            }
        }
    }

    protected function GetSumColumn($column)
    {
        if($column instanceof CDataColumn or $column instanceof CLinkColumn)
        {
            $sum=0;
            $data_grid=$this->dataProvider->getData();
            $n=count($data_grid);
            if($n>0)
            {
                for($row=0;$row<$n;++$row)
                {
                    $data = $data_grid[$row];
                    $value=0;
                    if($column instanceof CDataColumn)
                    {
                        if($column->value!==null)
                        {
                            $value=$column->evaluateExpression($column->value,array('data'=>$data,'row'=>$row));
                        }
                        else
                        {
                            if($column->name!==null)
                            {
                                $value=CHtml::value($data,$column->name);
                            }
                        }
                        $sum=$sum+$value;
                    }
                    else
                    {
                        if($column->labelExpression!==null)
                        {
                            $value=$column->evaluateExpression($column->labelExpression,array('data'=>$data,'row'=>$row));
                        }
                        else
                        {
                            $value=$this->label;
                        }
                        $sum=$sum+$value;
                    }
                }
           }
           return $sum;
        }
        else
        {
            return 0;
        }
    }

    /**
	 * Renders the data items for the grid view.
	 */
	public function renderItems()
	{
		if($this->dataProvider->getItemCount()>0 || $this->showTableOnEmpty)
		{
			echo "<table class=\"{$this->itemsCssClass}\">\n";
			$this->renderTableHeader();
			$this->renderTableFooter();
			$this->renderTableBody();
			echo "</table>";
		}
		else
			$this->renderEmptyText();
	}

	/**
	 * Renders the table header.
	 */
	public function renderTableHeader()
	{
		echo "<thead>\n<tr>\n";
        if($this->hasTotal and 'Header' === $this->totalPlace )
        {
            foreach($this->totalValue as $value)
            {
                if ( isset($value['htmlOptions']))
                {
                    echo CHtml::openTag('th',$value['htmlOptions']);
                }
                else
                {
                    echo '<th>';
                }
                if(is_null($value['value']))
                {
                    echo $this->nullDisplay;
                }
                else
                {
                    if(!isset($value['type']))
                    {
                         echo $value['value'];
                    }
                    else
                    {
                        echo $this->formatter->format($value['value'],$value['type']);
                    }
                }
                 echo '</th>';
            }
            echo "\n</tr>\n<tr>\n";
        }
        foreach($this->columns as $column)
        {
            $column->renderHeaderCell();
        }
		echo "\n</tr>\n</thead>\n";
	}
}
