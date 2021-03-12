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
            if($request->request->has('username') and
            $request->request->has('password') and
            $request->request->has('firstname') and
            $request->request->has('surname') and
            $request->request->has('phone') and
            $request->request->has('email')) {
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
                    $response->setStatusCode(403);
                } elseif($res === 0) {
                    $response->setStatusCode(500);
                }
            } else {
                $response->setStatusCode(400);
            }
            // Login
        } elseif($request->query->getAlnum('action') == 'login') {
            if($request->request->has('Username') and 
            $request->request->has('Password')) {
                $res = $session->get('sessionOBJ')->login($request->request->getAlnum('Username'),
                $request->request->get('Password'));
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
        } else {
            $response->setStatusCode(400);
        }
    }
    // check if user account exists
    if($request->getMethod() == 'GET') {
        if($request->query->getAlnum('action') == 'accountexists') {
            if($request->query->has('Username')) {
                $res = $RoyalShorelineHotelDB->userExists($request->query->getAlnum('Username'));
                if($res) {
                    $response->setStatusCode(400);
                } else {
                    $response->setStatusCode(204);
                }
            }
            // is Logged In
        } elseif($request->query->getAlnum('action') == 'isloggedin') {
            $res = $session->get('sessionOBJ')->isLoggedIn();
            if($res == false) {
                $response->setStatusCode(403);
            } elseif(count($res) == 1) {
                $response->setStatusCode(200);
                $response->setContent(json_encode($res));
            }
            // Logout
        } elseif($request->query->getAlnum('action') == 'logout') {
            $session->get('sessionOBJ')->logout();
            $response->setStatusCode(200);
        } else {
            $response->setStatusCode(400);
        }
    }
    // delete Account
    if($request->getMethod() == 'DELETE') {
        if($request->query->getAlnum('action') == 'deleteAccount') {
            if($request->request->has('registerid')) {
                $res = $session->get('sessionOBJ')->deleteAccount(
                    $request->request->getAlnum('rid')
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

    // Add Room
    if($request->getMethod() == 'POST') {
        if($request->query->getAlnum('action') == 'addRoom') {
            if($request->request->has('roomimage') and
            $request->request->has('roomtype') and
            $request->request->has('roomprice') and
            $request->request->has('roomdescription')) {
                $res = $session->get('sessionOBJ')->addRoom(
                    $request->request->getAlnum('rimg'),
                    $request->request->get('rtype'),
                    $request->request->get('rprice'),
                    $request->request->get('rdescript')
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
    } if($request->getMethod() == 'DELETE') {
        if($request->query->getAlnum('action') == 'deleteRoom') {
            if($request->request->has('roomid')) {
                $res = $session->get('sessionOBJ')->deleteRoom(
                    $request->request->getAlnum('roomid')
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
        // Update Room
        if($request->getMethod() == 'PUT') {
            if($request->query->getAlnum('action') == 'updateRoom') {
                if($request->request->has('roomid') and
                $request->request->has('roomimage') and
                $request->request->has('roomtype') and
                $request->request->has('roomprice') and
                $request->request->has('roomdescription')) {
                    $res = $session->get('sessionOBJ')->updateRoom(
                        $request->request->has('rmid') and
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
        }
          // make Booking
        if($request->getMethod() == 'POST') {
            if($request->query->getAlnum('action') == 'makeBooking') {
                if($request->request->has('registerid') and
                $request->request->has('roomid') and
                $request->request->has('roomimage') and
                $request->request->has('roomtype') and
                $request->request->has('bookingdate') and
                $request->request->has('numberofadult') and
                $request->request->has('numberofchildren') and
                $request->request->has('checkindate') and
                $request->request->has('checkoutdate')) {
                    $res = $session->get('sessionOBJ')->makeBooking(
                        $request->request->getAlnum('rid'),
                        $request->request->get('roomid'),
                        $request->request->get('rmimage'),
                        $request->request->get('rmtype'),
                        $request->request->get('bookingdate'),
                        $request->request->get('numberofadult'),
                        $request->request->get('numberofchildren'),
                        $request->request->get('checkindate')
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
        // update Booking
        if($request->getMethod() == 'PUT') {
            if($request->query->getAlnum('action') == 'updateBooking') {
                if($request->request->has('registerid') and
                $request->request->has('roomid') and
                $request->request->has('roomimage') and
                $request->request->has('roomtype') and
                $request->request->has('bookingdate') and
                $request->request->has('numberofadult') and
                $request->request->has('numberofchildren') and
                $request->request->has('checkindate') and
                $request->request->has('checkoutdate')) {
                    $res = $session->get('sessionOBJ')->updateBooking(
                        $request->request->getAlnum('rid'),
                        $request->request->get('roomid'),
                        $request->request->get('rmimage'),
                        $request->request->get('rmtype'),
                        $request->request->get('bookingdate'),
                        $request->request->get('numberofadult'),
                        $request->request->get('numberofchildren'),
                        $request->request->get('checkindate')
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
        } // delete booking
        if($request->getMethod() == 'DELETE') {
            if($request->query->getAlnum('action') == 'deleteBooking') {
                if($request->request->has('bookingid')) {
                    $res = $session->get('sessionOBJ')->deleteBooking(
                        $request->request->getAlnum('bookingid')
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
    } 
    else {
        $redirect = new RedirectResponse($_SERVER['REQUEST_URI']);
    }
    
    // print response message
    $response->send();
?>