package com.springboot;

import org.apache.ibatis.annotations.Mapper;
import org.mybatis.spring.annotation.MapperScan;
import org.springframework.boot.SpringApplication;
import org.springframework.boot.autoconfigure.SpringBootApplication;


@SpringBootApplication
@MapperScan("com.springboot.mapper")
public class WorkSpace2Application {

    public static void main(String[] args) {
        SpringApplication.run(WorkSpace2Application.class, args);
    }

}
