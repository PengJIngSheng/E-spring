package com.springboot.controller;


import com.springboot.mapper.FunctionMapper;
import com.springboot.pojo.Cart;
import com.springboot.pojo.Product;
import com.springboot.pojo.User;
import jakarta.servlet.http.HttpServletRequest;
import jakarta.servlet.http.HttpSession;
import lombok.AllArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.authentication.AnonymousAuthenticationToken;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContext;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.security.core.userdetails.UserDetails;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@Controller
@AllArgsConstructor
public class CartController {

    @Autowired
    private FunctionMapper functionMapper;

    @Autowired
    private HttpServletRequest request;
    @Autowired
    private Cart cart;

    @PostMapping("/savecart")
    public String saveCart(@ModelAttribute Cart cart, @RequestParam("product.productid") String productId, HttpServletRequest request) {
        String custid = (String) request.getSession().getAttribute("custid");
        if (custid != null) {
            cart.setCustid(custid);
            List<Cart> cartItems = functionMapper.getCartItems(custid);
            System.out.println(cartItems);
            Product product = functionMapper.getProductById(productId);
            cart.setProductid(product.getProductid());
            Integer existingQuantity = functionMapper.getQuantityInCart(custid, productId);
            if (existingQuantity == null) {
                existingQuantity = 0;
            }
            if (existingQuantity + cart.getProductquantity() > 10) {
                return "redirect:/Productfunction/" + product.getProductid() + "?error=exceeded";
            }
            cart.setProductname(product.getProductname());
            cart.setProductprice(product.getProductprice());
            cart.setTotalprice(cart.getProductprice() * cart.getProductquantity());
            int updatedRows = functionMapper.updateCart(cart);
            if (updatedRows == 0) {
                functionMapper.insertCart(cart);
            }
            return "redirect:/Shoppingcart";
        } else {
            return "redirect:/login";
        }
    }

    @GetMapping("/Shoppingcart")
    public String viewShoppingCart(Model model, HttpSession session) {
        // 验证用户是否已登录
        String custid = (String) session.getAttribute("custid");
        if (custid == null) {
            // 用户未登录，执行逻辑，如重定向到登录页面
            return "redirect:/login";
        }

        List<Cart> cartItems = functionMapper.getCartItems(custid);
        model.addAttribute("cartItems", cartItems);

        return "Shoppingcart"; // 返回购物车页面
    }
}


