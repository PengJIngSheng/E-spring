package com.springboot.controller;


import ch.qos.logback.core.model.Model;
import com.springboot.mapper.FunctionMapper;
import com.springboot.pojo.Cart;
import com.springboot.pojo.Product;
import com.springboot.pojo.User;
import lombok.AllArgsConstructor;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.security.core.Authentication;
import org.springframework.security.core.context.SecurityContextHolder;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;

@Controller
@AllArgsConstructor
public class CartController {

    @Autowired
    private FunctionMapper functionMapper;

    @Autowired
    private Product product;

    @PostMapping("savecart")
    public String saveCart(@ModelAttribute Cart cart, Authentication auth) {
        auth = SecurityContextHolder.getContext().getAuthentication();
        if (auth == null || auth.getPrincipal() == null) {
            return "login";
        }
        User user = (User) auth.getPrincipal();
        cart.setProductid(product.getProductid());
        cart.setCustid(user.getCustid());
        if (cart.getProductquantity() < 1 || cart.getProductquantity() > 5) {
            return "redirect:/Productdetails/" + cart.getProductid();
        }
        cart.setTotalprice(cart.getProductprice() * cart.getProductquantity());
        functionMapper.insertCart(cart);
        return "Productdetails";
    }
}