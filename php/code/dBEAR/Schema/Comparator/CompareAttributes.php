<?php
/**
 * Copyright (c) 2013, Praxigento
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification, are permitted provided that the
 * following conditions are met:
 *  - Redistributions of source code must retain the above copyright notice, this list of conditions and the following
 *      disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright notice, this list of conditions and the
 *      following disclaimer in the documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
 * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
 * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY,
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
/**
 * User: Alex Gusev <flancer64@gmail.com>
 */

namespace dBEAR\Schema\Comparator;


use dBEAR\Schema\Domain\Attribute;

class CompareAttributes
{
    /**
     * Return 'true' in case of two attributes are enough equal (except notes).
     * @param $attr1
     * @param $attr2
     * @return bool
     */
    public static function equalsEnough($attr1, $attr2)
    {
        $result = false;
        if (($attr1 instanceof Attribute) && ($attr2 instanceof Attribute)) {
            if (
                ($attr1->getAlias() == $attr2->getAlias()) &&
                ($attr1->getEntity() == $attr2->getEntity()) &&
                ($attr1->getName() == $attr2->getName()) &&
                /** ($attr1->getNotes() == $attr2->getNotes()) && */
                ($attr1->isRequired() == $attr2->isRequired()) &&
                ($attr1->isTemporal() == $attr1->isTemporal())
            ) {
                $result = true;
            }
        }
        return $result;
    }
}