<?
function route() {

    /*
    Bir URL'yi parçalara ayırarak dinamik bir rota yönlendirme işlevi gerçekleştirir. İşlem şu adımları takip eder:
    İstenen URL'yi $_SERVER['REQUEST_URI'] ile alır ve / karakterine göre böler.
    */

    $url = $_SERVER['REQUEST_URI'];
    $parts = explode('/', $url);

    /*
    $controller, $action, $param1 ve $param2 değişkenleri, URL'nin parçalarına dayanarak ilgili değerleri belirler.
    Eğer parça belirli bir sıraya göre mevcut değilse, varsayılan değerler atanır.
    */

    $controller = !empty($parts[2]) ? $parts[2] : 'welcome';
    $action = !empty($parts[3]) ? $parts[3] : 'index';
    $param1 = !empty($parts[4]) ? $parts[4] : $action;
    $param2 = !empty($parts[5]) ? $parts[5] : $action;

    /*
    İlgili denetleyici dosyasının yolunu belirlemek için $controllerFile değişkeni oluşturulur. 
    Bu yol, app/controllers/ dizini altında ilgili denetleyici dosyasını temsil eder.
    Eğer denetleyici dosyası varsa, require_once($controllerFile) ile dosya dahil edilir.
    */

    $controllerFile = 'app/controllers/' . ucfirst($controller) . 'Controller.php';

    if (file_exists($controllerFile)) {
        require_once($controllerFile);
    
    /*
    Ardından, ilgili model dosyasının yolunu belirlemek için $modelFile değişkeni oluşturulur. 
    Bu yol, app/models/ dizini altında ilgili model dosyasını temsil eder.
    Eğer model dosyası varsa, require_once($modelFile) ile dosya dahil edilir.
    */

        $modelFile = 'app/models/' . ucfirst($controller) . 'Model.php';
        if (file_exists($modelFile)) {
            require_once($modelFile);
    
    /*
    İlgili denetleyici sınıfının adını temsil eden $className değişkeni oluşturulur.
    Eğer ilgili denetleyici sınıfı varsa, ilgili model sınıfının adını temsil eden $modelClassName değişkeni oluşturulur.
    Eğer ilgili model sınıfı varsa, veritabanı bağlantısı için $dbConfig ve $db nesneleri oluşturulur.
    Bu adımlar, veritabanıyla etkileşim için gerekli olan sınıf ve bağlantıyı oluşturmayı sağlar.
    */

            $className = ucfirst($controller) . 'Controller';
            if (class_exists($className)) {
                $modelClassName = ucfirst($controller) . 'Model';
                if (class_exists($modelClassName)) {

                    // Veritabanı dahilinde çalıştırılır

                    $dbConfig = new DatabaseConnection(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
                    $db = $dbConfig->getConnection();

    /*
    İlgili model ve denetleyici nesneleri, oluşturulan sınıfların örneklerini oluşturmak.
    için $modelObj ve $controllerObj değişkenleriyle temsil edilir.
    Eğer ilgili denetleyici sınıfında $action adında bir yöntem (method) varsa, bu yöntem çağırılır. 
    $param1 ve $param2 varsa, ilgili yönteme bu parametreler aktarılır. Eğer sadece $param1 varsa, ilgili yönteme sadece bu parametre aktarılır. 
    Eğer hiçbir parametre yoksa, ilgili yöntem çağrılır.
    */
    
                    $modelObj = new $modelClassName($db);
                    $controllerObj = new $className($modelObj);

                    // Model ve controller nesneleri oluşturulur

                    if (method_exists($controllerObj, $action)) {
                        if ($param1 !== null && $param2 !== null) {
                            $controllerObj->$action($param1, $param2);
                        } elseif ($param1 !== null) {
                            $controllerObj->$action($param1);
                        } else {
                            $controllerObj->$action();
                        }
    /*
    Eğer ilgili denetleyici sınıfında belirtilen $action yöntemi yoksa, "Geçersiz action" mesajı ekrana yazdırılır.
    */

                    } else {
                        echo 'Geçersiz action';
                    }
    /*
   Veritabanı bağlantısı kapatılır ($db->close()).
    */
                    $db->close();

    /*
    Eğer ilgili model sınıfı yoksa, "Geçersiz model" mesajı ekrana yazdırılır.
    */

                } else {
                    echo 'Geçersiz model';
                }
    /*
    Eğer ilgili denetleyici sınıfı yoksa, "Geçersiz controller" mesajı ekrana yazdırılır.
    */
            } else {
                echo 'Geçersiz controller';
            }
    /*
    Eğer ilgili model dosyası bulunamazsa, "Model dosyası bulunamadı" mesajı ekrana yazdırılır.
    */
        } else {
            echo 'Model dosyası bulunamadı';
        }
    /*
    Eğer ilgili denetleyici dosyası bulunamazsa, "Controller dosyası bulunamadı" mesajı ekrana yazdırılır.
    */
    } else {
        echo 'Controller dosyası bulunamadı';
    }
}

    /*
    route() işlevi çağrılarak yönlendirme işlemi başlatılır.
    */
route();

?>