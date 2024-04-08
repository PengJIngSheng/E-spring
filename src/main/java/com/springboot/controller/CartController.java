package com.springboot.controller;


import ch.qos.logback.core.model.Model;
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
import org.springframework.web.bind.annotation.ModelAttribute;
import org.springframework.web.bind.annotation.PostMapping;
import org.springframework.web.bind.annotation.RequestParam;

@Controller
@AllArgsConstructor
public class CartController {

    @Autowired
    private FunctionMapper functionMapper;

    @Autowired
    private HttpServletRequest request;

    @PostMapping("/savecart")
    public String saveCart(@ModelAttribute Cart cart, @RequestParam("product.productid") String productId, HttpServletRequest request) {
        String custid = (String) request.getSession().getAttribute("custid");
        if (custid != null) {
            cart.setCustid(custid);
            Product product = functionMapper.getProductById(productId);
            cart.setProductid(product.getProductid());
            int existingQuantity = functionMapper.getQuantityInCart(custid, productId);
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
}


