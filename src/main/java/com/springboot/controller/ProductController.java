package com.springboot.controller;

import com.springboot.mapper.FunctionMapper;
import com.springboot.pojo.Product;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Controller;
import org.springframework.ui.Model;
import org.springframework.web.bind.annotation.PathVariable;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
public class ProductController {
    @Autowired
    private FunctionMapper functionMapper;

    @RequestMapping("/Bookdetails/{id}")
    public String bookDetails(@PathVariable("id") String id, Model model) {
        Product product = functionMapper.getProductById(id);
        model.addAttribute("product", product);
        return "Bookdetails";
    }
}
