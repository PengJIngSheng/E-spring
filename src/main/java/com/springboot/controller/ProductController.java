package com.springboot.controller;

import com.springboot.mapper.FunctionMapper;
import com.springboot.pojo.Product;
import jakarta.servlet.http.HttpServletRequest;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;
import org.springframework.web.bind.annotation.SessionAttributes;

@Controller
public class ProductController {
    @Autowired
    private FunctionMapper functionMapper;

    @RequestMapping("/Productfunction/{id}")
    public String bookDetails(@PathVariable("id") String id, Model model) {
        Product product = functionMapper.getProductById(id);
        product.setProductid(id);
        System.out.println(product.getProductid());
        String imagePath = product.getProductimage();
        imagePath = imagePath.replace("\uFEFF", ""); //删除字符串中的BOM
        product.setProductimage(imagePath);
        model.addAttribute("product", product);
        model.addAttribute("productId", id);
        return "Productdetails";
    }
}