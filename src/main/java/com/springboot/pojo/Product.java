package com.springboot.pojo;

import lombok.AllArgsConstructor;
import lombok.Getter;
import lombok.NoArgsConstructor;
import lombok.Setter;
import org.springframework.stereotype.Component;

@Getter
@Setter
@NoArgsConstructor
@AllArgsConstructor
@Component
public class Product {

    private String produdctid;

    private String productname;

    private String productauthour;

    private String productype;

    private Double productprice;

    private String productimage;

    private String productdec;

}
