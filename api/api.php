<?php 
require_once('../vendor/autoload.php');
require_once('./db.php');
require_once('./se.php');

$RoyalShorelineHotelDB = new RoyalShorelineHotelModel;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\HttpFoundation\RedirectResponse;

$request = Request::createFromGlobals();
$response = new Response();
$session = new Session(new NativeSessionStorage(), new AttributeBag());

$response->headers->set('Content-Type', 'application/json');
$response->headers->set('Access-Control-Allow-Headers', 'origin, content-type, accept');
$response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
$response->headers->set('Access-Control-Allow-Credentials', 'true');

// Start session
$session->start();

if(!$session->has('sessionOBJ')) {
    $session->set('sessionOBJ', new RoyalShorelineSession);
}

if(empty($request->query->all())) {
    $response->setStatusCode(400);
} elseif($request->cookies->has('PHPSESSID')) {
    if($session->get('sessionOBJ')->is_rate_limited()) {
        $response->setStatusCode(429);
    }
    // Register
    if($request->getMethod() == 'POST') {
        if($request->query->getAlnum('action') == 'register') {
            if($request->request->has('uname') and
            $request->request->has('upass') and
            $request->request->has('rfirstname') and
            $request->request->has('rsurname') and
            $request->request->has('rphone') and
            $request->request->has('remail')) {
                $res = $session->get('sessionOBJ')->register(
                    $request->request->getAlnum('uname'),
                    $request->request->get('upass'),
                    $request->request->get('rfirstname'),
                    $request->request->get('rsurname'),
                    $request->request->get('rphone'),
                    $request->request->get('remail')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                    
                } elseif($res === false) {
                    $response->setStatusCode(404);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            } else {
                $response->setStatusCode(400);
            }
            // Login
        } elseif($request->query->getAlnum('action') == 'login') {
            if($request->request->has('uname') and 
            $request->request->has('upass')) {
                $res = $session->get('sessionOBJ')->Login($request->request->getAlnum('uname'),
                $request->request->get('upass'));
                if($res === false) {
                    $response->setStatusCode(401);
                } elseif(count($res) == 1) {
                    $response->setStatusCode(203);
                    $response->setContent(json_encode($res));
                } elseif(count($res) > 1) {
                    $response->setStatusCode(200);
                    $response->setContent(json_encode($res));
                }
            } else {
                $response->setStatusCode(400);
            }
        }  // logout
        elseif($request->query->getAlnum('action') == 'logout') {
              $session->get('sessionOBJ')->logout();
              $response->setStatusCode(200);
          } else {
              $response->setStatusCode(400);
          }
    }
   
    // delete Account
    if($request->getMethod() == 'POST') {
        if($request->query->getAlnum('action') == 'deleteAccount') {
            if($request->request->has('rid')) {
                $res = $session->get('sessionOBJ')->deleteAccount(
                    $request->request->getAlnum('registerid')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                    } elseif($res === false) {
                        $response->setStatusCode(403);
                    } elseif($res === 0) {
                        $response->setStatusCode(500);
                    }
                } else {
                    $response->setStatusCode(400);
                }
            }
        }
    
    // Display Room Lists
    
    // Add Room
    if($request->getMethod() == 'POST') {
        if($request->query->getAlnum('action') == 'addRoom') {
            if($request->request->has('rmimg') and
            $request->request->has('rmtype') and
            $request->request->has('rmprice') and
            $request->request->has('rmdescript')) {
                $res = $session->get('sessionOBJ')->addRoom(
                    $request->request->getAlnum('rmimg'),
                    $request->request->get('rmtype'),
                    $request->request->get('rmprice'),
                    $request->request->get('rmdescript')
                );
                if($res === true) {
                    $response->setStatusCode(201);
                } elseif($res === false) {
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            } else {
                $response->setStatusCode(400);
            }
        }
        // Delete Room
    } if($request->getMethod() == 'POST') {
        if($request->query->getAlnum('action') == 'deleteRoom') {
            if($request->request->has('roomid')) {
                $res = $session->get('sessionOBJ')->deleteRoom(
                    $request->request->getAlnum('roomid')
                );
                if($res === true) {
                    $response->setStatusCode(202);
                    } elseif($res === false) {
                        $response->setStatusCode(403);
                    } elseif($res === 0) {
                        $response->setStatusCode(500);
                    }
                } else {
                    $response->setStatusCode(400);
                }
            }
        }
        // Update Room
        if($request->getMethod() == 'POST') {
            if($request->query->getAlnum('action') == 'updateRoom') {
                if($request->request->has('roomid') and
                $request->request->has('rmimg') and
                $request->request->has('rmtype') and
                $request->request->has('rmprice') and
                $request->request->has('rmdescript')) {
                    $res = $session->get('sessionOBJ')->updateRoom(
                        $request->request->get('roomid'),
                        $request->request->get('rmimg'),
                        $request->request->get('rmtype'),
                        $request->request->get('rmprice'),
                        $request->request->get('rmdescript')
                    );
                    if($res === true) {
                        $response->setStatusCode(202);
                    } elseif($res === false) {
                        $response->setStatusCode(403);
                    } elseif($res === 0) {
                        $response->setStatusCode(500);
                    }
                } else {
                    $response->setStatusCode(400);
                }
            }
        }
          // make Booking
        if($request->getMethod() == 'POST') {
            if($request->query->getAlnum('action') == 'makeBooking') {
                if($request->request->has('rid') and
                $request->request->has('rmid') and
                $request->request->has('rmimg') and
                $request->request->has('rmtype') and
                $request->request->has('bookdate') and
                $request->request->has('numofadult') and
                $request->request->has('numofchild') and
                $request->request->has('ckindate') and
                $request->request->has('ckoutdate')) {
                    $res = $session->get('sessionOBJ')->makeBooking(
                        $request->request->getAlnum('rid'),
                        $request->request->get('rmid'),
                        $request->request->get('rmimg'),
                        $request->request->get('rmtype'),
                        $request->request->get('bookdate'),
                        $request->request->get('numofadult'),
                        $request->request->get('numofchild'),
                        $request->request->get('ckindate'),
                        $request->request->get('ckoutdate')
                    );
                    if($res === true) {
                        $response->setStatusCode(201);
                    } elseif($res === false) {
                        $response->setStatusCode(403);
                    } elseif($res === 0) {
                        $response->setStatusCode(500);
                    }
                } else {
                    $response->setStatusCode(400);
                }
            }
        }
        // view Bookings

        // update Booking
        if($request->getMethod() == 'POST') {
            if($request->query->getAlnum('action') == 'updateBooking') {
                if($request->request->has('bookid') and
                $request->request->get('rid') and
                $request->request->get('rmid') and
                $request->request->get('rmimg') and
                $request->request->has('rmtype') and
                $request->request->has('bookdate') and
                $request->request->has('numofadult') and
                $request->request->has('numofchild') and
                $request->request->has('ckindate') and
                $request->request->has('ckoutdate')) {
                    $res = $session->get('sessionOBJ')->updateBooking(
                        $request->request->getAlnum('bookid'),
                        $request->request->get('rid'),
                        $request->request->get('rmid'),
                        $request->request->get('rmimg'),
                        $request->request->get('rmtype'),
                        $request->request->get('bookdate'),
                        $request->request->get('numofadult'),
                        $request->request->get('numofchildren'),
                        $request->request->get('ckindate'),
                        $request->request->get('ckoutdate')
                    );
                    if($res === true) {
                        $response->setStatusCode(202);
                    } elseif($res === false) {
                        $response->setStatusCode(403);
                    } elseif($res === 0) {
                        $response->setStatusCode(500);
                    }
                } else {
                    $response->setStatusCode(400);
                }
            }
        } // delete booking
        if($request->getMethod() == 'POST') {
            if($request->query->getAlnum('action') == 'deleteBooking') {
                if($request->request->has('bookingid')) {
                    $res = $session->get('sessionOBJ')->deleteBooking(
                        $request->request->getAlnum('bookingid')
                    );
                    if($res === true) {
                        $response->setStatusCode(202);
                        } elseif($res === false) {
                            $response->setStatusCode(403);
                        } elseif($res === 0) {
                            $response->setStatusCode(500);
                        }
                    } else {
                        $response->setStatusCode(400);
                    }
                }
            }
        } 
        else {
        $redirect = new RedirectResponse($_SERVER['REQUEST_URI']);
    }
    
    // print response message
    $response->send();
?>