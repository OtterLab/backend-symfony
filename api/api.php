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
$response->headers->set('Access-Control-Allow-Origin', 'http://localhost/');
$response->headers->set('Access-Control-Allow-Credentials', 'true');

// start session
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

    if($request->getMethod() == 'POST') {
        if($request->query->getAlnum('action') == 'register') {
            if($request->request->has('username') and
                $request->request->has('password') and
                $request->request->has('firstname') and
                $request->request->has('surname') and
                $request->request->has('phone') and
                $request->request->has('email')) {
                    $res = $session->get('sessionOBJ')->register(
                        $request->request->getAlnum('username'),
                        $request->request->get('password'),
                        $request->request->get('firstname'),
                        $request->request->get('surname'),
                        $request->request->get('phone'),
                        $request->request->get('email')
                    );
                    if($res === true) {
                        $response->setStatusCode(201);
                        $response->setContent(json_encode($res));
                    } else {
                        $response->setStatusCode(403);
                    }
                } else {
                    $response->setStatusCode(400);
                }
            } 
            
            elseif($request->query->getAlnum('action') == 'login') {
                if($request->request->has('username') and
                    $request->request->has('password')) {
                        $res = $session->get('sessionOBJ')->Login($request->request->getAlpha('username'),
                        $request->request->get('password'));
                        if($res === false) {
                            $response->setContent(json_encode($request->request));
                            $response->setStatusCode(401);
                        } elseif(count($res) > 1) {
                            $response->setStatusCode(200);
                            $response->setContent(json_encode($res));
                        }
                    } else {
                        $response->setStatusCode(400);
                    }
            } elseif($request->query->getAlnum('action') == 'logout') {
                $session->get('sessionOBJ')->logout();
                $response->setStatusCode(200);
            }

            elseif($request->query->getAlnum('action') == 'addRoom') {
                if($request->request->has('roomtype') and
                    $request->request->has('roomprice') and
                    $request->request->has('roomdesc')) {
                        $res = $session->get('sessionOBJ')->addRoom(
                            $request->request->getAlnum('roomtype'),
                            $request->request->get('roomprice'),
                            $request->request->get('roomdesc')
                        );
                        if($res === true) {
                            $response->setStatusCode(201);
                            $response->setContent(json_encode($res));
                        } else {
                            $response->setStatusCode(403);
                        }
                    } else {
                        $response->setStatusCode(400);
                    }
                } 
            
            elseif($request->query->getAlnum('action') == 'updateRoom') {
                if($request->request->has('roomid') and
                $request->request->has('roomtype') and
                    $request->request->has('roomprice') and
                    $request->request->has('roomdesc')) {
                        $res = $session->get('sessionOBJ')->updateRoom(
                            $request->request->getAlnum('roomid'),
                            $request->request->get('roomtype'),
                            $request->request->get('roomprice'),
                            $request->request->get('roomdesc')
                        );
                        if($res === true) {
                            
                            $response->setStatusCode(202);
                            $response->setContent(json_encode($res));
                        } else {
                            $response->setStatusCode(403);
                        }
                    } else {
                        $response->setStatusCode(400);
                    }
                } 
             
            elseif($request->query->getAlnum('action') == 'deleteRoom') {
                if($request->request->has('roomid')) {
                    $res = $session->get('sessionOBJ')->deleteRoom(
                        $request->request->getAlnum('roomid')
                    );
                    if($res === true) {
                        $response->setStatusCode(202);
                        $response->setContent(json_encode($res));
                    } else {
                        $response->setStatusCode(403);
                    }
                } else {
                    $response->setStatusCode(400);
                }
            }

            elseif($request->query->getAlnum('action') == 'makeBooking') {
                if($request->request->has('registerid') and
                    $request->request->has('roomid') and
                    $request->request->has('roomtype') and
                    $request->request->has('bookingdate') and
                    $request->request->has('numofadult') and
                    $request->request->has('numofchild') and
                    $request->request->has('checkindate') and
                    $request->request->has('checkoutdate')) {
                        $res = $session->get('sessionOBJ')->makeBooking(
                            $request->request->getAlnum('registerid'),
                            $request->request->get('roomid'),
                            $request->request->get('roomtype'),
                            $request->request->get('bookingdate'),
                            $request->request->get('numofadult'),
                            $request->request->get('numofchild'),
                            $request->request->get('checkindate'),
                            $request->request->get('checkoutdate')
                        );
                        if($res === true) {
                            $response->setStatusCode(201);
                            $response->setContent(json_encode($res));
                        } else {
                            $response->setStatusCode(403);
                        }
                    } else {
                        $response->setStatusCode(400);
                    }
                }

            elseif($request->query->getAlnum('action') == 'updateBooking') {
                if($request->request->has('bookingid') and
                    $request->request->has('registerid') and
                    $request->request->has('roomid') and
                    $request->request->has('roomtype') and
                    $request->request->has('bookingdate') and
                    $request->request->has('numofadult') and
                    $request->request->has('numofchild') and
                    $request->request->has('checkindate') and
                    $request->request->has('checkoutdate')) {
                        $res = $session->get('sessionOBJ')->updateBooking(
                            $request->request->getAlnum('bookingid'),
                            $request->request->get('registerid'),
                            $request->request->get('roomid'),
                            $request->request->get('roomtype'),
                            $request->request->get('bookingdate'),
                            $request->request->get('numofadult'),
                            $request->request->get('numofchild'),
                            $request->request->get('checkindate'),
                            $request->request->get('checkoutdate')
                        );
                        if($res === true) {
                            $response->setStatusCode(201);
                            $response->setContent(json_encode($res));
                        } else {
                            $response->setStatusCode(403);
                        }
                    } else {
                        $response->setStatusCode(400);
                    }
            }

            elseif($request->query->getAlnum('action') == 'deleteBooking') {
                if($request->request->has('bookingid')) {
                    $res = $session->get('sessionOBJ')->deleteBooking(
                        $request->request->getAlnum('bookingid')
                    );
                    if($res === true) {
                        $response->setStatusCode(202);
                        $response->setContent(json_encode($res));
                    } else {
                        $response->setStatusCode(403);
                    }
                } else {
                    $response->setStatusCode(400);
                }
            }

            if($request->getMethod() == 'GET') {
                if($request->query->getAlnum('action') == 'showRooms') {
                    if($request->request->has('roomid') and 
                        $request->request->has('roomtype') and
                        $request->request->has('roomprice') and
                        $request->request->has('roomdesc')) {
                            $res = $session->get('sessionOBJ')->showRooms(
                                $request->request->getAlnum('roomid'),
                                $request->request->get('roomtype'),
                                $request->request->get('roomprice'),
                                $request->request->get('roomdesc')
                            );
                            if($res === true) {
                                $response->setStatusCode(200);
                                $response->setContent(json_encode($res));
                            } else {
                                $response->setStatusCode(404);
                            }
                        } else {
                            $response->setStatusCode(400);
                        }
                    } 

                    elseif($request->query->getAlnum('action') == 'showBooking') {
                        if($request->request->has('registerid') and
                            $request->request->has('roomid') and
                            $request->request->has('roomtype') and
                            $request->request->has('bookingdate') and
                            $request->request->has('numofadult') and
                            $request->request->has('numofchild') and
                            $request->request->has('checkindate') and
                            $request->request->has('checkoutdate')) {
                                $res = $session->get('sessionOBJ')->showBooking(
                                    $request->request->getAlnum('registerid'),
                                    $request->request->get('roomid'),
                                    $request->request->get('roomtype'),
                                    $request->request->get('bookingdate'),
                                    $request->request->get('numofadult'),
                                    $request->request->get('numofchild'),
                                    $request->request->get('checkindate'),
                                    $request->request->get('checkoutdate')
                                );
                                if($res === true) {
                                    $response->setStatusCode(200);
                                    $response->setContent(json_encode($res));
                                } else {
                                    $response->setStatusCode(404);
                                }
                            } else {
                                $response->setStatusCode(400);
                            }
                        }
            }
        }
    }
    else {
        $redirect = new RedirectResponse($_SERVER['REQUEST_URI']);
    }

    // send response code
    $response->send();

?>