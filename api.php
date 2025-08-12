
<?php
header('Content-Type: application/json; charset=utf-8');

$DATA_FILE = __DIR__ . '/data.json';
if(!file_exists($DATA_FILE)){
    file_put_contents($DATA_FILE, json_encode([]));
}

$action = $_POST['action'] ?? '';

if($action === 'encrypt'){
    $message = ($_POST['message'] ?? '');
    $message = trim($message);
    if($message === ''){
        echo json_encode(['success'=>false, 'error'=>'Empty message']);
        exit;
    }
    $key = strtoupper(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 6));
    $stored = base64_encode($message);

    $tries = 0;
    while(true){
        $fp = fopen($DATA_FILE,'c+');
        if(!$fp){
            echo json_encode(['success'=>false,'error'=>'Unable to open data file']);
            exit;
        }
        if(flock($fp, LOCK_EX)){
            $contents = stream_get_contents($fp);
            $arr = json_decode($contents,true);
            if(!is_array($arr)) $arr = [];
            $exists = array_column($arr,'key');
            while(in_array($key,$exists)){
                $key = strtoupper(substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyz'), 0, 6));
            }
            $arr[] = ['key'=>$key,'message'=>$stored,'created'=>time()];
            ftruncate($fp,0);
            rewind($fp);
            fwrite($fp,json_encode($arr, JSON_UNESCAPED_SLASHES));
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);
            break;
        }
        fclose($fp);
        if(++$tries > 5) break;
        usleep(50000);
    }

    echo json_encode(['success'=>true,'key'=>$key]);
    exit;
}

if($action === 'decrypt'){
    $key = strtoupper(trim($_POST['key'] ?? ''));
    if($key === ''){
        echo json_encode(['success'=>false,'error'=>'Empty key']);
        exit;
    }

    $tries = 0;
    while(true){
        $fp = fopen($DATA_FILE,'c+');
        if(!$fp){
            echo json_encode(['success'=>false,'error'=>'Unable to open data file']);
            exit;
        }
        if(flock($fp, LOCK_EX)){
            $contents = stream_get_contents($fp);
            $arr = json_decode($contents,true);
            if(!is_array($arr)) $arr = [];
            $foundIndex = null;
            foreach($arr as $i => $item){
                if(isset($item['key']) && strtoupper($item['key']) === $key){
                    $foundIndex = $i;
                    $found = $item;
                    break;
                }
            }
            if($foundIndex === null){
                flock($fp, LOCK_UN);
                fclose($fp);
                echo json_encode(['success'=>false,'error'=>'Key not found or already viewed']);
                exit;
            }
            array_splice($arr,$foundIndex,1);
            ftruncate($fp,0);
            rewind($fp);
            fwrite($fp,json_encode($arr, JSON_UNESCAPED_SLASHES));
            fflush($fp);
            flock($fp, LOCK_UN);
            fclose($fp);

            $message = base64_decode($found['message']);
            echo json_encode(['success'=>true,'message'=>$message]);
            exit;
        }
        fclose($fp);
        if(++$tries > 5) break;
        usleep(50000);
    }

    echo json_encode(['success'=>false,'error'=>'Lock error']);
    exit;
}

echo json_encode(['success'=>false,'error'=>'Invalid action']);