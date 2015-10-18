<?php
class YMongoLogRoute extends CLogRoute {
	public $connectionName;
	public $collectionName = 'yiilog';
	public $categoryLevel =  0;

	protected function processLogs($logs) {
		$mongo = Yii::app()->getComponent($this->connectionName, true);
		$collection = $mongo->{$this->collectionName};
		foreach($logs as $log) {
			if ($this->categoryLevel > 0) {
				$arr = preg_split('/\./', $log[2]);
				$cat = join('.', array_slice($arr, $this->categoryLevel));
			}
			else
				$cat = $log[2];

			$fields = array(
					'level' => $log[1],
					'cat' => $cat,
					'ts' => new MongoDate(round($log[3])),
			);
			$logData = json_decode($log[0], true);
			if (isset($logData['raw'])) {
				$rawData = $logData['raw'];
				unset($logData['raw']);
			}

			if (is_array($logData))
				$fields += $logData;
			else
				$fields['data'] = $logData;

			$collection->insert($fields);

			// save rawData to file
			if (isset($rawData)) {
				$rawLogPath = Yii::app()->runtimePath . '/raw/' . date('Y/m/d/');
				if (!file_exists($rawLogPath)) mkdir($rawLogPath, 0755, true);

				file_put_contents($rawLogPath . (string)$fields['_id'] . '.gz', gzencode($rawData));
			}
		}
	}
}
?>