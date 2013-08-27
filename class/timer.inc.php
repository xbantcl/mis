<?php 
//==================================================== 
//		FileName:timer.inc.php 
//		Summary: ��ʱ���� 
//		 
//==================================================== 
class timer  
{ 
	var $startTime;	//��ʼʱ�� 
	var $endTime;	//����ʱ�� 
 
	//========================================== 
	// ����: timer() 
	// ����: constructor 
	// ����: no 
	//========================================== 
	function timer()  
	{ 
		$this->start(); 
	} 
 
	//========================================== 
	// ����: start() 
	// ����: ������ʱ�� 
	// ����: no 
	//========================================== 
	function start()  
	{ 
		$this->startTime = $this->getMicroTime(); 
	} 
	 
	//========================================== 
	// ����: getExecuteTime() 
	// ����: ���ض�ʱ���ļ�ʱ���� 
	// ����: no 
	//========================================== 
 
	function getExecuteTime()  
	{ 
		$this->endTime = $this->getMicroTime(); 
		$excuteTime = ($this->endTime - $this->startTime) * 1000; 
		return round($excuteTime, 4); 
	} 
	//========================================== 
	// ����: getMicroTime() 
	// ����: ���ص�ǰʱ��ĺ���ֵ 
	// ����: no 
	//========================================== 
	function getMicroTime() 
	{  
		$timer = explode(" ",microtime());  
		return ((float)$timer[0] + (float)$timer[1]);  
    }  
 
} 
?> 
