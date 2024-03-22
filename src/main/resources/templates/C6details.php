<?php
session_set_cookie_params(0);
session_start();
require('include/db.php');

$quantity_reached_5 = false;

if (isset($_SESSION['loginuser'])) {
    $name = "LATTE";
    $custid = $_SESSION['loginuser']['custid'];
    $price = "16.70";
    $price = floatval($price);
    $protection = $_POST['protect'];

    $stmt = $conn->prepare("SELECT `quantity` FROM `cart` WHERE `productname` = ? AND `custid` = ?");
    $stmt->bind_param("ss", $name, $custid);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $quantity = $row['quantity'];
        if ($quantity >= 5) {
            $quantity_reached_5 = true;
        }
    }
    if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["submit"])) {
        $trimmed = array_map('trim', $_POST);
        if ($quantity_reached_5) {
            $gift = $_POST['gift'];
            $stmt = $conn->prepare("UPDATE `cart` SET `protection` = ?, `giftpaper` = ? WHERE `productname` = ? AND `custid` = ?");
            $stmt->bind_param("ssss", $protection, $gift, $name, $custid);
            if ($stmt->execute()) {
                echo '<script>alert("Updated Cart");</script>';
            } else {
                echo '<script>alert("Cannot update cart");</script>';
            }
        } else if (isset($trimmed['quantity']) && is_numeric($trimmed['quantity'])) {
            $new_quantity = $trimmed['quantity'];

            if ($new_quantity > 0 && $new_quantity <= 5) {
                $gift = $_POST['gift'];
                $img = "CoffeeProduct/c6.png";

                if ($result->num_rows > 0) {
                    if ($quantity < 5) {
                        $sum_quantity = $quantity + $new_quantity;
                        if ($sum_quantity <= 5) {
                            $new_quantity = $sum_quantity;
                            $stmt = $conn->prepare("UPDATE `cart` SET `quantity` = ?, `protection` = ?, `giftpaper` = ? WHERE `productname` = ? AND `custid` = ?");
                            $stmt->bind_param("issss", $new_quantity, $protection, $gift, $name, $custid);
                            if ($stmt->execute()) {
                                $quantity_reached_5 = ($new_quantity == 5);
                                echo '<script>alert("Updated Cart");</script>';
                            } else {
                                echo '<script>alert("Cannot update cart");</script>';
                            }
                        } else {
                            echo '<script>alert("Cannot order more than 5 coffee");</script>';
                        }
                    } else {
                        $stmt = $conn->prepare("UPDATE `cart` SET `protection` = ?, `giftpaper` = ? WHERE `productname` = ? AND `custid` = ?");
                        $stmt->bind_param("ssss", $protection, $gift, $name, $custid);
                        if ($stmt->execute()) {
                            echo '<script>alert("Updated Cart");</script>';
                        } else {
                            echo '<script>alert("Cannot update cart");</script>';
                        }
                    }
                } else {
                    $stmt = $conn->prepare("INSERT INTO `cart` (custid, productname, price, protection, quantity, giftpaper, productimage) values (?, ?, ?, ?, ?, ?, ?)");
                    $stmt->bind_param("sssssss", $custid, $name, $price, $protection, $new_quantity, $gift, $img);
                    if ($stmt->execute()) {
                        $quantity_reached_5 = ($new_quantity == 5);
                        echo '<script>alert("Saved to cart");</script>';
                    } else {
                        echo '<script>alert("Cannot save to cart");</script>';
                    }
                }
            } else {
                echo '<script>alert("Each user can only order 5 coffee");</script>';
            }
        }
    }
} else {
    if ($_SERVER["REQUEST_METHOD"] == 'POST' && isset($_POST["submit"])) {
        echo '<script>alert("Login First to save to cart");window.location.href="login.php";</script>';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="css/Bookdetails.css">
</head>
<body>
<div class="Banner">
    <div class="Nav">
        <a href="Mainpage.html"><img id="logo" src="Logo2.png" alt=""/></a>
        <div class="Sub-Nav">
            <a href="Bookproduct.html"><p>SHOP BOOK</p></a>
            <a href="coffeeproduct.html"><p>COFFEE</p></a>
            <a href="FAQ.html"><p>FAQ</p></a>
        </div>
        <div class="Icon-Nav">
            <a href="https://www.instagram.com/shanmu_cafe/">
                <svg
                        width="17"
                        height="16"
                        viewBox="0 0 17 16"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                            d="M8.08008 3.89257C7.35697 3.88762 6.64604 4.07878 6.02294 4.44571C5.40224 4.80577 4.88651 5.32189 4.52694 5.94286C4.1596 6.56585 3.96804 7.27679 3.97265 8C3.96769 8.72311 4.15886 9.43403 4.52579 10.0571C4.88584 10.6778 5.40197 11.1936 6.02294 11.5531C6.64593 11.9205 7.35686 12.112 8.08008 12.1074C8.80319 12.1124 9.51411 11.9212 10.1372 11.5543C10.7579 11.1942 11.2736 10.6781 11.6332 10.0571C12.0006 9.43415 12.1921 8.72321 12.1875 8C12.1925 7.27689 12.0013 6.56597 11.6344 5.94286C11.2743 5.32217 10.7582 4.80644 10.1372 4.44686C9.51423 4.07952 8.80329 3.88796 8.08008 3.89257ZM8.08008 10.6789C7.72773 10.6841 7.37802 10.6172 7.05253 10.4822C6.72703 10.3471 6.43264 10.1469 6.18751 9.89371C5.934 9.64854 5.73344 9.35401 5.5982 9.0283C5.46297 8.7026 5.39593 8.35263 5.40122 8C5.39608 7.64756 5.4632 7.29781 5.59843 6.97231C5.73366 6.64681 5.93414 6.35247 6.18751 6.10743C6.43255 5.85406 6.72689 5.65359 7.05239 5.51835C7.37789 5.38312 7.72764 5.31601 8.08008 5.32114C8.43251 5.31601 8.78227 5.38312 9.10777 5.51835C9.43327 5.65359 9.72761 5.85406 9.97265 6.10743C10.226 6.35247 10.4265 6.64681 10.5617 6.97231C10.697 7.29781 10.7641 7.64756 10.7589 8C10.7642 8.35235 10.6973 8.70206 10.5623 9.02755C10.4272 9.35305 10.2269 9.64743 9.97379 9.89257C9.72862 10.1461 9.43409 10.3466 9.10838 10.4819C8.78268 10.6171 8.43271 10.6842 8.08008 10.6789ZM13.3304 3.71429C13.3317 3.58761 13.307 3.46202 13.2578 3.34526C13.2087 3.2285 13.1361 3.12305 13.0446 3.03543C12.957 2.94394 12.8516 2.87139 12.7348 2.82225C12.6181 2.7731 12.4925 2.74842 12.3658 2.74971C12.2391 2.74842 12.1135 2.7731 11.9968 2.82225C11.88 2.87139 11.7746 2.94394 11.6869 3.03543C11.5954 3.12305 11.5229 3.2285 11.4738 3.34526C11.4246 3.46202 11.3999 3.58761 11.4012 3.71429C11.3999 3.84096 11.4246 3.96655 11.4738 4.08331C11.5229 4.20007 11.5954 4.30552 11.6869 4.39314C11.7746 4.48463 11.88 4.55719 11.9968 4.60633C12.1135 4.65547 12.2391 4.68015 12.3658 4.67886C12.4899 4.68114 12.6131 4.65682 12.727 4.60754C12.841 4.55825 12.943 4.48515 13.0264 4.39314C13.2025 4.20841 13.3099 3.96874 13.3304 3.71429ZM16.0447 4.67886C16.0126 4.07273 15.9047 3.47301 15.7235 2.89371C15.5161 2.30933 15.1806 1.77869 14.7418 1.34057C14.3037 0.901723 13.773 0.566314 13.1887 0.358857C12.6155 0.17915 12.0209 0.0769104 11.4207 0.0548575C10.741 0.0182861 9.62751 0 8.08008 0C6.53265 0 5.41951 0.0179047 4.74065 0.0537142C4.1404 0.0757671 3.54579 0.178007 2.97265 0.357714C2.38827 0.56517 1.85763 0.90058 1.41951 1.33943C0.980658 1.77755 0.645249 2.30819 0.437792 2.89257C0.258072 3.46571 0.155832 4.06032 0.133792 4.66057C0.0979829 5.33943 0.0800781 6.45257 0.0800781 8C0.0800781 9.54743 0.0979829 10.6606 0.133792 11.3394C0.155832 11.9397 0.258072 12.5343 0.437792 13.1074C0.645249 13.6918 0.980658 14.2225 1.41951 14.6606C1.85316 15.0969 2.38609 15.4215 2.97265 15.6069C3.54066 15.8121 4.13711 15.9277 4.74065 15.9497C5.41951 15.9832 6.53265 16 8.08008 16C9.62751 16 10.7406 15.9821 11.4195 15.9463C12.0197 15.9246 12.6143 15.8228 13.1875 15.6434C13.7719 15.436 14.3025 15.1006 14.7407 14.6617C15.1795 14.2236 15.5149 13.693 15.7224 13.1086C15.9021 12.5354 16.0043 11.9408 16.0264 11.3406C16.0622 10.661 16.0801 9.54743 16.0801 8C16.0801 6.45257 16.0683 5.34552 16.0447 4.67886ZM14.3304 12.7143C14.1943 13.0625 13.9875 13.3787 13.7232 13.6431C13.4588 13.9074 13.1426 14.1142 12.7944 14.2503C12.1677 14.4329 11.519 14.529 10.8664 14.536C10.3658 14.5596 9.62751 14.5714 8.65151 14.5714H7.50865C6.55627 14.5714 5.81836 14.5596 5.29494 14.536C4.6426 14.5248 3.99449 14.4288 3.36694 14.2503C3.01852 14.1143 2.70206 13.9076 2.4375 13.6433C2.17294 13.3789 1.96602 13.0626 1.82979 12.7143C1.65132 12.0867 1.55528 11.4386 1.54408 10.7863C1.52046 10.2621 1.50865 9.52381 1.50865 8.57143V7.42857C1.50865 6.47619 1.52046 5.73829 1.54408 5.21486C1.55108 4.56218 1.6472 3.91352 1.82979 3.28686C1.95857 2.93446 2.16275 2.61442 2.42805 2.34912C2.69336 2.08381 3.01339 1.87964 3.36579 1.75086C3.99371 1.57229 4.64221 1.47624 5.29494 1.46514C5.81836 1.44076 6.55627 1.42857 7.50865 1.42857H8.65151C9.60389 1.42857 10.3422 1.44038 10.8664 1.464C11.5194 1.47089 12.1685 1.56702 12.7955 1.74971C13.1479 1.8785 13.4679 2.08267 13.7332 2.34798C13.9986 2.61328 14.2027 2.93331 14.3315 3.28571C14.5142 3.91274 14.6103 4.56179 14.6172 5.21486C14.6408 5.71467 14.6527 6.45295 14.6527 7.42971V8.57257C14.6527 9.52495 14.6408 10.2632 14.6172 10.7874C14.6056 11.4394 14.5091 12.0872 14.3304 12.7143Z"
                            fill="black"
                    />
                </svg>
            </a>
            <a href="Contact.php">
                <svg
                        width="17"
                        height="12"
                        viewBox="0 0 17 12"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                >
                    <path
                            d="M14.5801 1.2985e-05C14.7775 -0.00347848 14.9736 0.0334916 15.1563 0.108647C15.3389 0.183802 15.5043 0.295552 15.6421 0.437013C15.7838 0.574868 15.8959 0.740316 15.9712 0.923145C16.0465 1.10597 16.0836 1.3023 16.0801 1.50001V10.5C16.0836 10.6975 16.0466 10.8936 15.9714 11.0762C15.8963 11.2589 15.7845 11.4242 15.6431 11.562C15.5052 11.7038 15.3398 11.8158 15.1569 11.8911C14.9741 11.9665 14.7778 12.0035 14.5801 12H1.58008C1.38244 12.0036 1.18615 11.9667 1.00333 11.8916C0.820505 11.8164 0.655018 11.7046 0.517078 11.563C0.375493 11.4251 0.263672 11.2596 0.188512 11.0768C0.113353 10.8939 0.0764491 10.6977 0.0800778 10.5V1.50001C0.0765864 1.30254 0.113557 1.10645 0.188712 0.923805C0.263867 0.741162 0.375617 0.575837 0.517078 0.438013C0.654933 0.296247 0.820381 0.18424 1.00321 0.108907C1.18604 0.0335743 1.38237 -0.0034863 1.58008 1.2985e-05H14.5801ZM14.5801 1.50001H1.58008V2.78101C2.30941 3.36435 3.71574 4.46835 5.79908 6.09301L6.08008 6.34401C6.39236 6.62091 6.72681 6.87175 7.08008 7.09401C7.36716 7.32316 7.71451 7.46419 8.08008 7.50001C8.44565 7.46419 8.793 7.32316 9.08008 7.09401C9.43334 6.87175 9.7678 6.62091 10.0801 6.34401L10.3611 6.09401C12.4444 4.49001 13.8507 3.38568 14.5801 2.78101V1.50001ZM1.58008 10.5H14.5801V4.68801L10.9551 7.56301C10.5563 7.91221 10.1277 8.22574 9.67408 8.50001C9.19747 8.8072 8.64675 8.97995 8.08008 9.00001C7.50413 8.97758 6.94401 8.80524 6.45508 8.50001C6.00843 8.21547 5.58054 7.90249 5.17408 7.56301L1.58008 4.68801V10.5Z"
                            fill="black"
                    />
                </svg>
            </a>
            <a href="login.php">
                <svg
                        width="17"
                        height="16"
                        viewBox="0 0 17 16"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                >
                    <g clip-path="url(#clip0_240_42)">
                        <path
                                d="M8.09502 0.00502752C9.54636 -0.000257347 10.9719 0.388637 12.2195 1.1302C13.4671 1.87177 14.4899 2.93818 15.1788 4.21561C15.8677 5.49304 16.1968 6.93356 16.131 8.38341C16.0652 9.83326 15.6069 11.238 14.8051 12.4478C14.0033 13.6576 12.8881 14.6269 11.5785 15.2524C10.2688 15.8779 8.81391 16.1361 7.36903 15.9993C5.92414 15.8626 4.54349 15.3361 3.37445 14.476C2.2054 13.6159 1.29183 12.4545 0.731243 11.1158C0.683885 11.0301 0.655008 10.9354 0.646469 10.8378C0.637931 10.7403 0.649921 10.642 0.681669 10.5494C0.713416 10.4567 0.764215 10.3717 0.830796 10.2999C0.897377 10.2281 0.978262 10.171 1.06824 10.1324C1.15822 10.0937 1.25531 10.0743 1.35324 10.0754C1.45116 10.0766 1.54777 10.0982 1.63683 10.1389C1.72589 10.1797 1.80543 10.2386 1.87033 10.312C1.93523 10.3853 1.98405 10.4714 2.01364 10.5648C2.03277 10.62 2.05622 10.6736 2.08377 10.7251C2.70483 12.1043 3.77995 13.2288 5.12982 13.9112C6.47968 14.5936 8.0227 14.7926 9.50152 14.475C10.9803 14.1573 12.3056 13.3423 13.2562 12.1659C14.2068 10.9894 14.7254 9.52256 14.7254 8.01001C14.7254 6.49746 14.2068 5.03063 13.2562 3.85416C12.3056 2.67768 10.9803 1.86267 9.50152 1.54506C8.0227 1.22745 6.47968 1.42643 5.12982 2.10882C3.77995 2.79122 2.70483 3.91577 2.08377 5.29493C2.05646 5.37424 2.02296 5.45129 1.98359 5.52536C1.9015 5.6719 1.76886 5.78354 1.61046 5.83942C1.45206 5.8953 1.27873 5.8916 1.12286 5.82901C0.966994 5.76642 0.839246 5.64921 0.763488 5.4993C0.68773 5.34939 0.669145 5.17702 0.711205 5.0144C1.05712 4.10931 1.57416 3.27933 2.23406 2.56983C2.9787 1.75797 3.88462 1.11051 4.89385 0.668861C5.90308 0.227214 6.9934 0.00111399 8.09502 0.00502752Z"
                                fill="black"
                        />
                        <path
                                d="M9.4977 7.23856L8.29545 6.01628C8.18835 5.91363 8.11768 5.77884 8.09417 5.63237C8.07066 5.4859 8.09561 5.33576 8.16521 5.20476C8.22562 5.07315 8.32881 4.96586 8.45797 4.90037C8.58714 4.83488 8.73467 4.81504 8.87654 4.84408C9.03861 4.87978 9.18769 4.95952 9.30735 5.07451C10.0988 5.85598 10.8903 6.63744 11.6718 7.42892C11.7498 7.49475 11.8125 7.57682 11.8555 7.6694C11.8985 7.76198 11.9208 7.86284 11.9208 7.96492C11.9208 8.06701 11.8985 8.16786 11.8555 8.26044C11.8125 8.35302 11.7498 8.43509 11.6718 8.50093L9.30735 10.8954C9.24277 10.9633 9.16507 11.0174 9.07895 11.0543C8.99284 11.0913 8.90011 11.1103 8.80641 11.1103C8.7127 11.1103 8.61998 11.0913 8.53386 11.0543C8.44775 11.0174 8.37005 10.9633 8.30547 10.8954C8.24258 10.8262 8.19418 10.7451 8.1631 10.6569C8.13202 10.5687 8.11889 10.4752 8.12449 10.3818C8.13009 10.2885 8.15431 10.1972 8.19571 10.1133C8.23711 10.0295 8.29487 9.95473 8.36558 9.89353C8.7463 9.5028 9.13703 9.12209 9.4977 8.68126H1.01182C0.895138 8.69098 0.777849 8.69098 0.661166 8.68126C0.501743 8.65759 0.355877 8.57816 0.249486 8.4571C0.143095 8.33603 0.0830703 8.18117 0.0800781 8.02003C0.0891495 7.85098 0.159061 7.69092 0.27691 7.56939C0.39476 7.44786 0.552592 7.37306 0.721278 7.35879H9.46765L9.4977 7.23856Z"
                                fill="black"
                        />
                    </g>
                    <defs>
                        <clipPath id="clip0_240_42">
                            <rect
                                    width="16.03"
                                    height="15.9899"
                                    fill="white"
                                    transform="translate(0.0800781 0.0050354)"
                            />
                        </clipPath>
                    </defs>
                </svg>
            </a>
            <a href="Shoppingcart.php">
                <svg
                        width="19"
                        height="16"
                        viewBox="0 0 19 16"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg"
                >
                    <g clip-path="url(#clip0_240_49)">
                        <path
                                d="M18.882 3.82646L17.607 8.86773C17.4181 9.62333 17.2292 10.3671 17.0757 11.1345C17.064 11.2464 17.0301 11.3549 16.9762 11.4536C16.9222 11.5523 16.8492 11.6394 16.7613 11.7097C16.6734 11.7799 16.5725 11.8321 16.4643 11.8631C16.3562 11.894 16.2429 11.9032 16.1312 11.8901H6.68619C6.06046 11.8901 5.82433 11.5596 5.67085 11.0283C4.83261 8.17116 3.99436 5.32586 3.20334 2.46874C3.18044 2.28374 3.09566 2.11191 2.96278 1.98117C2.82989 1.85043 2.65671 1.76845 2.47136 1.74856C1.86842 1.69858 1.27377 1.57569 0.70042 1.38257C0.546835 1.30942 0.413057 1.20047 0.310333 1.06488C0.207609 0.929283 0.138943 0.771006 0.110107 0.603355C0.110107 0.142912 0.51152 -0.0696009 0.971964 0.00123655C1.85743 0.190137 2.73109 0.390843 3.65198 0.55613C4.24229 0.662387 4.33674 1.13464 4.46661 1.59508C5.1986 4.28691 6.01323 6.96692 6.7098 9.67055C6.88689 10.3317 7.15844 10.497 7.81959 10.4852C10.4524 10.4852 13.097 10.4852 15.777 10.4852L17.2764 4.12162H8.17378C7.64249 4.12162 6.99315 4.12162 6.99315 3.42505C6.99315 2.72848 7.63069 2.70487 8.17378 2.70487C11.3615 2.70487 14.5373 2.70487 17.725 2.70487C18.1286 2.74432 18.5254 2.83559 18.9057 2.97641C19.0709 3.02364 19.0001 3.37782 18.882 3.82646Z"
                                fill="black"
                        />
                        <path
                                d="M7.77241 15.9987C8.58746 15.9987 9.24819 15.338 9.24819 14.5229C9.24819 13.7079 8.58746 13.0471 7.77241 13.0471C6.95736 13.0471 6.29663 13.7079 6.29663 14.5229C6.29663 15.338 6.95736 15.9987 7.77241 15.9987Z"
                                fill="black"
                        />
                        <path
                                d="M15.0687 15.9987C15.8837 15.9987 16.5445 15.338 16.5445 14.5229C16.5445 13.7079 15.8837 13.0471 15.0687 13.0471C14.2536 13.0471 13.5929 13.7079 13.5929 14.5229C13.5929 15.338 14.2536 15.9987 15.0687 15.9987Z"
                                fill="black"
                        />
                    </g>
                    <defs>
                        <clipPath id="clip0_240_49">
                            <rect
                                    width="18.89"
                                    height="15.9975"
                                    fill="white"
                                    transform="translate(0.110107 0.00125122)"
                            />
                        </clipPath>
                    </defs>
                </svg>
            </a>
        </div>
    </div>
</div>
<div class="Keeper">
    <div class="Product-image">
        <p class="p1">01</p>
        <p class="p2">/</p>
        <p class="p3">02</p>
        <img src="CoffeeProduct/c6.png" alt="">
        <div class="Left-button">
            <button><</button>
        </div>
        <div class="Right-button">
            <button>></button>
        </div>
    </div>
    <form action="C6details.php" method="post">
        <div class="Product-info">
            <div class="Bookinfo">
                <p class="Name">LATTE</p>
                <p class="About">US.</p>
                <p class="Type">COFFEE: Classics</p>
                <p class="Price">¥ 16.70<span>税込</span></p>
            </div>
            <div class="desc">
                <p>Coffee is more than just a beverage. It is a symbol of sophistication, elegance and indulgence.
                    For those who appreciate the finer things in life, coffee offers a variety of flavors, aromas and
                    experiences that can elevate any moment. Whether it is a rich espresso, a smooth latte or a decadent
                    cappuccino, coffee can satisfy your cravings and delight your senses. Coffee is not only a luxury,
                    but
                    also a necessity for many people who rely on its stimulating effects to boost their energy and
                    productivity.
                </p>
            </div>
            <div class="extra-protection">
                <p class="p1">Need Extra Protection ?</p>
                <div class="Link">
                    <input class="i1" type="button" placeholder="Glass cup" value="Glass cup">
                    <input class="i2" type="button" placeholder="Plastic cup" value="Plastic cup">
                    <input type="hidden" name="protect" id="protect">
                </div>
                <p class="p2"></p>
                <select name="gift">
                    <option value="6 shots">6 shots</option>
                    <option value="4 shots">4 shots</option>
                </select>
                <?php if ($quantity_reached_5 || !$result->num_rows) : ?>
                    <input id="quantity" type="number" name="quantity"
                           placeholder="Not >5" <?php echo $quantity_reached_5 ? 'disabled' : ''; ?>>
                <?php else : ?>
                    <input id="quantity" type="number" name="quantity" placeholder="Quantity">
                <?php endif; ?>
                <div class="Link2">
                    <button type="submit" value="submit" name="submit">ADD TO CART</button>
                </div>
                <p class="p3">Publisher</p>
                <div class="desc2">
                    <p>Apple.INC, America limited
                    </p>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    var img = document.querySelector('.Product-image img');
    var p1 = document.querySelector('.Product-image .p1');
    var images = ['CoffeeProduct/c6.png', 'image2.jpg'];
    var currentIndex = 0;
    var leftButton = document.querySelector('.Product-image .Left-button button');
    var rightButton = document.querySelector('.Product-image .Right-button button');
    leftButton.addEventListener('click', function () {
        if (currentIndex > 0) {
            currentIndex--;
            img.src = images[currentIndex];
            p1.textContent = '0' + (currentIndex + 1);
        }
    });

    rightButton.addEventListener('click', function () {
        if (currentIndex < images.length - 1) {
            currentIndex++;
            img.src = images[currentIndex];
            p1.textContent = '0' + (currentIndex + 1);
        }
    });

    const buttons = document.querySelectorAll('.Link input[type="button"]');
    const protectInput = document.querySelector('#protect');
    let selectedValue = localStorage.getItem('selectedValue');

    buttons.forEach(button => {
        if (button.value === selectedValue) {
            button.classList.add('selected');
            protectInput.value = button.value;
        }

        button.addEventListener('click', () => {
            const selectedValue = button.value;
            buttons.forEach(b => b.classList.remove('selected'));
            button.classList.add('selected');
            protectInput.value = button.value;
            localStorage.setItem('selectedValue', selectedValue);
            // Send Ajax request
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'C6details.php');
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                if (xhr.status === 200) {
                } else if (xhr.status !== 200) {
                }
            };
            xhr.send(encodeURI(`protect=${selectedValue}`));
        });
    });

    window.onload = function () {
        var quantityInput = document.getElementById('quantity');
        var quantityReached5 = <?php echo $quantity_reached_5 ? 'true' : 'false'; ?>;

        if (quantityReached5) {
            quantityInput.disabled = true;
            quantityInput.style.backgroundColor = '#000000';
            quantityInput.placeholder = '5';
        }
    };
</script>
</body>
</html>
