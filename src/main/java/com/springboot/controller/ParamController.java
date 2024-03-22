package com.springboot.controller;

import jakarta.servlet.http.HttpServletRequest;
import org.springframework.stereotype.Controller;
import org.springframework.web.bind.annotation.RequestMapping;

@Controller
@RequestMapping("/demo")
public class ParamController {

    @RequestMapping("/param1")
    public String param1(HttpServletRequest request) {
        String name = request.getParameter("name");
        String password = request.getParameter("password");

        System.out.println("name = " + name);
        System.out.println("password = " + password);
        return "/param1.html";
    }

    @RequestMapping("/param3")
    public String param3(String name, Integer age) {
        System.out.println("name = " + name);
        System.out.println("age = " + age);
        return "param1";
    }

}
